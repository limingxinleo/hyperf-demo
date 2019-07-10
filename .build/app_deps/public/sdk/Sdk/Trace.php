<?php
namespace Sdk;
date_default_timezone_set('Asia/Shanghai');

class Trace
{
    public $client = null;
    static $object = null;
    static $env = "product";

    const PORT = 9981;
    const UDP_PORT = 9982;

    static $common_config = [];//一般配置，目前是选择的协议
    static $send_ip;//发送数据包的地址
    static $udp_clinet;//udp客户端

    public function __construct()
    {
        self::$object = $this;
//        $this->initClient();
    }

    public function initClient()
    {
        $client = new \swoole_client(SWOOLE_SOCK_TCP);
        $client->set([
            'open_length_check' => true,
            'package_length_type' => 'N',
            'package_length_offset' => 0, //第N个字节是包长度的值
            'package_body_offset' => 4, //第几个字节开始计算长度
            'package_max_length' => 2000000, //协议最大长度
        ]);
        $env_name = get_cfg_var('env.name');
        if (!empty($env_name)) {
            self::$env = $env_name;
        }
        if (self::$env == "local") {
            \StatsCenter::$debug = 1; //本地调试打开debug模式，上报不拼包
        }
        $ret = \CloudConfig::getCenterConfig(self::$env);
        if (!empty($ret['ip'])) {
            $res = $client->connect($ret['ip'], 9981, 0.5);
            if ($res) {
                $this->client = $client;
            }
        }
    }

    public function sendData($data)
    {
        if ($this->client) {
            $res = $this->client->send($data);
            //check response
            $recv = $this->client->recv();
            if (!$recv) {
                unset($this->client);
                $this->initClient();
                $res = $this->client->send($data);
            }
            return $res;
        }
        return false;
    }

    static function getInstance()
    {
        if (empty(self::$object)) {
            $object = new static();
        } else {
            $object = self::$object;
        }
        return $object;
    }

    public static function report($data)
    {
        //获取配置目前选择的协议
        self::getConfig();

        if (strtoupper(self::$common_config['protocol']) == 'UDP') {
            return self::sendWithUdp($data);
        } elseif (strtoupper(self::$common_config['protocol']) == 'TCP') {
            return self::sendWithFile($data);
        } else {
            return false;
        }
    }

    public static function internal_getcid()
    {
    	// 兼容没有swoole的
		if (defined('SWOOLE_VERSION')) {
			if (SWOOLE_VERSION[0] > 1) {
				return \Swoole\Coroutine::getuid();
			} else {
				return -1;
			}
		} else {
			return -1;
		}
    }

    /**
     * getSendIp 获取udp发送的ip
     */
    static public function getSendIp()
    {
        if (!self::$send_ip) {
            $env = get_cfg_var('env.name')?:'product';
            self::$send_ip = self::getConfigIp($env);
        }
    }

    /**
     * getConfig 获取配置文件
     * @return bool|mixed
     */
    static public function getConfig()
    {
        if (self::$common_config) return self::$common_config;

        $config_file = __DIR__ . "/../config_common.conf";
        if (is_file($config_file)) {
            self::$common_config = json_decode(file_get_contents($config_file), true);
            return true;
        }
        return false;
    }

    public static function getConfigIp($env)
    {
        $config_file = __DIR__ . "/../config_ip.conf";
        if (is_file($config_file)) {
            $data = json_decode(file_get_contents($config_file), 1);
            if (isset($data['ip'][$env])) {
                return $data['ip'][$env];
            }else{
                return current($data['ip']);
            }
        }
        return false;
    }

    /**
     * sendWithUdp 发送udp数据到swoole_admin
     * @param $data
     * @return bool
     */
    static public function sendWithUdp($data)
    {
        $info = unserialize($data);
        if(!is_array($info)){//反序列化失败
            return false;
        }

        self::getUdpClient();
        self::getSendIp();

        self::$udp_clinet->sendto(self::$send_ip,self::UDP_PORT,@\swoole_serialize::pack($info));
        return true;
    }

    /**
     * getUdpClient 获取udp客户端
     */
    static public function getUdpClient()
    {
        if (!self::$udp_clinet) {
            self::$udp_clinet = new \swoole_client(SWOOLE_SOCK_UDP);
        }
    }


}
