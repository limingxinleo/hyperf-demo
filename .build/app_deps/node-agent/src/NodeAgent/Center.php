<?php
namespace NodeAgent;
use Swoole\String;

/**
 * 中心服务器，中心服务器本身也是一个节点
 * @package NodeAgent
 */
class Center extends Server
{
    const PORT_UDP = 9508;
    const PORT_TCP = 9509;
    /**
     * @var \redis
     */
    public $redis;
    protected $nodes = array();

    /**
     * 600秒强制更新
     * @var int
     */
    protected $nodeInfoLifeTime = 60;

    /**
     * IP到NodeInfo的映射
     * @var array
     */
    protected $ipMap = array();

    /**
     * 当前版本
     * @var string
     */
    protected $nodeCurrentVersion;

    /**
     * 当前的NodeAgent包
     * array('version' => '1.0.1',
     * 'url' => 'http://183.57.37.215:9000/node-agent-1.0.1.phar',
     * 'hash' => '9d7fe2b25b4fa6412a4657d3cf181893',
     * )
     */
    protected $nodeCurrentPackage;

    const KEY_NODE_SOCKET = 'node:socket';
    const KEY_NODE_LIST = 'node:list';
    const KEY_NODE_INFO = 'node:info';
    const KEY_NODE_VERSION = 'node:version';

    function init()
    {
        $this->redis = \Swoole::$php->redis;
        $nodeList = $this->redis->sMembers(self::KEY_NODE_LIST);
        $this->nodeCurrentVersion = json_decode($this->redis->get(self::KEY_NODE_VERSION), true);
        $this->nodes = array_flip($nodeList);
        //监听UDP端口，接受来自于节点的上报
        $this->serv->addlistener('0.0.0.0', self::PORT_UDP, SWOOLE_SOCK_UDP);
        $this->serv->on('packet', array($this, 'onPacket'));
        NodeInfo::$serv = $this->serv;
        NodeInfo::$center = $this;

        $this->serv->on('WorkerStart', function (\swoole_server $serv, $worker_id)
        {
            //每1分钟向服务器上报
            $serv->tick(60000, [$this, 'onTimer']);
        });

        $this->log(__CLASS__.' is running.');
    }

    function onTimer()
    {
        $newVersion = $this->redis->get(self::KEY_NODE_VERSION);
        if ($newVersion)
        {
            $ver = json_decode($newVersion, true);
            if (!$ver)
            {
                $this->log("json_decode failed. error Version Config: {$newVersion}");
            }
            //有最新的版本
            if (String::versionCompare($ver['version'], $this->nodeCurrentVersion['version']) > 0)
            {
                $this->nodeCurrentVersion = $ver;
                $this->log("found new node-agent version [{$ver['version']}]");
            }
        }
        else
        {
            $this->log("redis->get(".self::KEY_NODE_VERSION.") failed.");
        }
    }

    function onPacket($serv, $data, $addr)
    {
        $req = unserialize($data);
        //错误的请求
        if (empty($req['cmd']))
        {
            return;
        }

        $ipAddress = $addr['address'];
        //没有建立映射
        if (empty($this->ipMap[$ipAddress]))
        {
            //建立映射
            if ($req['cmd'] == 'putInfo' and !empty($req['info']))
            {
                $nodeInfo = new NodeInfo();
                $nodeInfo->setInfo($req['info']);
                $nodeInfo->address = $ipAddress;
                $nodeInfo->port = $addr['port'];
                $this->ipMap[$ipAddress] = $nodeInfo;
                $this->log("new node, address=$ipAddress, version=" . $nodeInfo->version);
                //存储hostname
                $this->redis->sAdd(self::KEY_NODE_LIST, $nodeInfo->hostname);
            }
            else
            {
                $this->serv->sendto($addr['address'], $addr['port'], serialize([
                    'cmd' => 'getInfo',
                ]));
                //存储Socket用于强制升级
                $this->redis->hSet(self::KEY_NODE_SOCKET, $addr['address'], $addr['port']);
            }
        }
        else
        {
            $nodeInfo = $this->ipMap[$ipAddress];
            call_user_func([$this, '_udp_' . $req['cmd']], $nodeInfo, $req);
        }
    }

    /**
     * 心跳
     * @param NodeInfo $nodeInfo
     * @param array $req
     */
    protected function _udp_heartbeat($nodeInfo, $req)
    {
        //更新心跳时间
        $nodeInfo->hearbeatTime = time();
        $this->log("heartbeat, node=" . $nodeInfo->address . ", version=" . $nodeInfo->version);
        //信息过期了需要更新
        if ($nodeInfo->updateTime < $nodeInfo->hearbeatTime - $this->nodeInfoLifeTime)
        {
            $nodeInfo->send(['cmd' => 'getInfo']);
            return;
        }
        //没有版本号
        if (empty($nodeInfo->version))
        {
            $this->log($nodeInfo->address . " version is null");
        }
        //当前的NodeAgent版本更高，节点服务器需要更新了
        elseif (String::versionCompare($this->nodeCurrentVersion['version'], $nodeInfo->version) > 0)
        {
            $nodeInfo->send([
                'cmd' => 'upgrade',
                'url' => $this->nodeCurrentVersion['url'],
                'hash' => $this->nodeCurrentVersion['hash'],
                'version' => $this->nodeCurrentVersion['version'],
            ]);
            $this->log($nodeInfo->address . " upgrade to {$this->nodeCurrentVersion['version']}");
        }
    }

    /**
     * 心跳
     * @param NodeInfo $nodeInfo
     * @param array $req
     */
    protected function _udp_putInfo($nodeInfo, $req)
    {
        if (!empty($req['info']))
        {
            $nodeInfo->setInfo($req['info']);
            $this->log("putInfo, address=" . $nodeInfo->address . ", version=" . $nodeInfo->version);
        }
    }

    protected function _cmd_getNodeList($fd, $req)
    {
        $nodeList = $this->redis->sMembers(self::KEY_NODE_LIST);
        array_walk($nodeList, function (&$a)
        {
            $a = self::KEY_NODE_INFO . ':' . $a;
        });
        $nodeInfo = $this->redis->mget($nodeList);
        $this->sendResult($fd, 0, '', $nodeInfo);
    }
}

class NodeInfo
{
    /**
     * @var \swoole_server
     */
    static $serv;

    /**
     * @var Center
     */
    static $center;
    /**
     * 机器hostname
     */
    public $hostname;

    /**
     * IP列表
     */
    public $ipList;
    public $uname;

    /**
     * 心跳时间
     */
    public $hearbeatTime;

    /**
     * 信息更新时间
     */
    public $updateTime;

    /**
     * 机器硬件设备信息
     */
    public $deviceInfo;

    public $address;
    public $port;

    public $version;

    /**
     * @param $info
     */
    function setInfo($info)
    {
        $this->updateTime = time();

        $this->ipList = $info['ipList'];
        $this->hostname = $info['hostname'];
        $this->uname = $info['uname'];
        $this->deviceInfo = $info['deviceInfo'];
        $this->version = $info['version'];
        self::$center->redis->set(Center::KEY_NODE_INFO . ':' . $this->hostname, json_encode($info));
    }

    /**
     * 发送指令
     * @param $req
     */
    function send($req)
    {
        self::$serv->sendto($this->address, $this->port, serialize($req));
    }
}