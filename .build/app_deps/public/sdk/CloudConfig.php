<?php
/**
 * 从云端获取配置
 * Class CloudConfig
 */
class CloudConfig
{
    const CONFIG_PATH = '/opt/swoole/config';
    const HTTP_TIMEOUT = 1;
    const CONFIG_PORT_PATH = '/opt/swoole/public/sdk/config_port.conf';

    /**
     * 是否启用Etcd
     */
    static $etcdEnabled = false;

    /**
     * 记录文件加载的时间
     * @var array
     */
    static $keys = array();

    static $moduleRelationMap = array();

    /**
     * 获取配置
     * @param $key
     * @param $env
     * @return mixed|void
     * @throws Exception
     */
    static function get($key, $env = 'product')
    {
        $key = strtolower(trim($key));
        if (empty($key))
        {
            throw new \Exception("config id empty.");
        }
		return self::fetch($key, $env);
        //配置文件
//        $configFile = self::CONFIG_PATH . "/{$key}.conf";
//        //配置文件不存在，直接到云端拉取配置
//        if (!is_file($configFile))
//        {
//            return self::fetch($key, $env);
//        }
//
//        $fp = fopen($configFile, 'r');
//        //打开文件失败
//        if (!$fp)
//        {
//            return self::fetch($key, $env);
//        }
//        //加锁避免读取脏数据，其他进程可能正在写文件
//        if (flock($fp, LOCK_SH))
//        {
//            $content = '';
//            while(!feof($fp))
//            {
//                $content .= fread($fp, 8192);
//            }
//            //释放锁
//            flock($fp, LOCK_UN);
//            fclose($fp);
//            $_conf = json_decode($content, true);
//            //json_decode失败，不是数组，表明config文件异常了
//            if (empty($_conf) or !is_array($_conf))
//            {
//                return self::fetch($key, $env);
//            }
//            //从缓存文件中读取成功
//            else
//            {
//                self::saveLoadTime($key, $configFile);
//                return $_conf;
//            }
//        }
//        //加锁失败了
//        fclose($fp);
//        return self::fetch($key, $env);
    }

    /**
     * 记录读取过的文件
     * @param $key
     * @param $file
     */
    static function saveLoadTime($key, $file)
    {
        $key = ltrim(trim($key), '/');
        self::$keys[$key] = array('time' => time(), 'file' => $file);
    }

    /**
     * 仅用于处理Redis配置
     * @param $id
     * @param string $env
     * @return mixed|void
     * @throws Exception
     */
    static function getRedisConfig($id, $env = 'product')
    {
        $config = self::get($id, $env);
        if (empty($config))
        {
            return false;
        }

        $codis_config = array();
        $twemproxy_config = array();

        foreach($config as &$c)
        {
            //Codis集群
            //TODO: 后面完全替换掉Codis后，这里的Codis配置处理逻辑可以删掉
            if (!empty($c['codis']))
            {
                if (empty($codis_config[$c['codis']]))
                {
                    //得到Codis集群的IP列表
                    $codis_config[$c['codis']] = self::get($c['codis'], $env);
                }
                //随机选择一台Codis机器
                $svr = self::getServer($codis_config[$c['codis']]['servers']);
                $c['ip'] = $svr['host'];
                $c['port'] = $svr['port'];
                unset($c['codis']);
            }
            //Twemproxy集群
            elseif (!empty($c['twemproxy']))
            {
                if (empty($twemproxy_config[$c['twemproxy']]))
                {
                    //得到Twemproxy集群的IP列表
                    $twemproxy_config[$c['twemproxy']] = self::get($c['twemproxy'], $env);
                }
                //随机选择一台Twemproxy集群机器
                $svr = self::getServer($twemproxy_config[$c['twemproxy']]['servers']);

                //优先保留$c中的配置
                $c += $svr;
                unset($c['twemproxy']);
            }
        }

        return $config;
    }

    static function getTwRedisConfig($id,$env='product')
    {
        $config = self::get($id, $env);
        if (empty($config))
        {
            return false;
        }
        if ($config['type'] != 'twemproxy') {
            return false;
        }
        if (empty($config['servers'])) {
            return false;
        }
        return self::getServer($config['servers']);
    }

    static function getServer($servers)
    {
        $weight = 0;
        //移除不在线的节点
        foreach ($servers as $k => $svr)
        {
            //节点已掉线
            if ($svr['status'] == 'offline')
            {
                unset($servers[$k]);
                continue;
            }
            $weight += $svr['weight'];
        }

        //计算权重并随机选择一台机器
        $use = rand(0, $weight - 1);
        $weight = 0;
        foreach ($servers as $k => $svr)
        {
            $weight += $svr['weight'];
            //在权重范围内
            if ($use < $weight)
            {
                return $svr;
            }
        }
        //绝不会到这里
        return $servers[0];
    }

    static function getCenterConfig($env)
    {
        $str = file_get_contents(__DIR__ . "/config_ip.conf");
        if (empty($str)) {
            return false;
        }
        $data = json_decode($str, 1);
        if (isset($data['ip'][$env])) {
            $ret['ip'] = $data['ip'][$env];
            return $ret;
        }
        return false;
    }

    static function getAopConfig($params,$api='config')
    {
        //判断swoole-admin是否正常
        $doorFile = '/tmp/swoole/door.cnf';
        if (!is_file($doorFile)) {
            return false;
        }
        $door = file_get_contents($doorFile);
        if($door == 0){//nodeagent没起或不通
            return false;
        }
        $data = json_decode(file_get_contents(self::CONFIG_PORT_PATH),1);
        $params['sapi'] = php_sapi_name();
        $params = http_build_query($params);
        $url = 'http://' . StatsCenter::getNetSvrIP() . ':'.$data['SWOOLE_ADMIN_AOP_PORT'].'/'.$api.'/?'. $params;
        $ctx = stream_context_create(array(
                'http' => array(
                    'timeout' => self::HTTP_TIMEOUT,
                )
            )
        );
        return @file_get_contents($url, 0, $ctx);
    }

    /**
     * 从云端拉取配置
     * @param $key
     * @param $env
     * @return bool|mixed
     * @throws Exception
     */
    static function fetch($key, $env)
    {
        $_recv = self::getAopConfig([
            'env' => urlencode($env),
            'ckey' => urlencode($key)
        ]);
        //连接服务器
        if (!$_recv)
        {
            return false;
        }
        $_conf =  json_decode($_recv, true);
        if (isset($_conf['code']))
        {
            if (empty($_conf['data']))
            {
                return false;
            }
            else
            {
                return json_decode($_conf['data'],1);
            }
        }
        else
        {
            return false;
        }
    }

    /**
     * 将配置写入到磁盘
     * @param $key
     * @param $_conf
     * @return bool
     */
    static function saveConfigFile($key, $_conf)
    {
        $configFile = self::CONFIG_PATH . "/{$key}.conf";
        $dir = dirname($configFile);
        umask(0);
        if (!is_dir($dir))
        {
            mkdir($dir, 0777, true);
        }
        $fp = fopen($configFile, 'w+');
        //打开文件失败
        if (!$fp)
        {
            return false;
        }
        if (flock($fp, LOCK_EX))
        {
            $content = json_encode($_conf);
            $length = strlen($content);
            for ($written = 0; $written < $length; $written += $fwrite)
            {
                $fwrite = fwrite($fp, substr($content, $written));
                //写文件失败了
                if ($fwrite === false)
                {
                    flock($fp, LOCK_UN);
                    fclose($fp);
                    return false;
                }
            }
            flock($fp, LOCK_UN);
            fclose($fp);
            //必须为777
            chmod($configFile, 0777);
            //记录读取过的文件
            self::saveLoadTime($key, $configFile);
            return true;
        }
        fclose($fp);
        return false;
    }

    /**
     * 获取配置
     * @param $key
     * @param $env
     * @return bool|mixed
     * @throws \Exception
     */
//    static function getFromCloud($key, $env)
//    {
//        $aopsrv_ip = StatsCenter::getNetSvrIP();
//        //连接服务器
//        $cli = stream_socket_client('tcp://' . $aopsrv_ip . ':9904', $errno, $errstr, 1, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT);
//        if (!$cli)
//        {
//            return false;
//        }
//        //发送请求
//        if (!stream_socket_sendto($cli, "CONFIG {$env} {$key}\r\n"))
//        {
//            return false;
//        }
//        //循环接收数据，直到返回\r\n为止
//        $_recv = '';
//        while (true)
//        {
//            $tmp = fread($cli, 8192);
//            if ($tmp == false)
//            {
//                fclose($cli);
//                return false;
//            }
//            $_recv .= $tmp;
//            if (substr($_recv, -2, 2) == "\r\n")
//            {
//                break;
//            }
//        }
//
//        $_recv = trim($_recv);
//        $_conf =  json_decode($_recv, true);
//        if ($_conf)
//        {
//            return $_conf;
//        }
//        else
//        {
//            fclose($cli);
//            throw new ConfigNotFound("get service[{$key}@{$env}] config from AopNet-Server[{$aopsrv_ip}] is failed.");
//        }
//    }

}