<?php

class StatsCenter
{

    const INVALID_ID = 0;
    const SUCC = 1;
    const FAIL = 0;
    const TIME_OUT_STATUS = 4444;
    const PACK_STATS = 'NNCNNNN';
    const PORT_STATS = 9903;
    const UDP_PORT_STATS = 9905;
    const PORT_AOPNET = 9904;
    const STATS_PKG_LEN = 25; //单个包的长度
    const STATS_PKG_NUM = 58; //包数量，一定要低于MTU（最大传输单元）

    protected static $interface_tick = array();
    static $sc_svr_ip = ['127.0.0.1'];
    static $sc_svr_key = 'sys:mostats';
    static $tmpData = [];
    static $sc_svr_count = 1;
    static $aop_svr_key = 'sys:aopnet';
    static $default_aop_svr_ips = ['127.0.0.1'];
    static $module_id = 1000238; //默认值
    static $round_index = 0;
    static $enable = true;
    static $socket = []; //UDP socket
    static $debug = 0; //debug mode send one pkg once
    //cli模式下需要调用扩展的setModuleId方法手动将模块id注入到扩展层，同时设置一下cliModuleId属性 方便php层获取进程自身的模块id
    //fpm模式自动通过域名获取模块id
    static $cliModuleId = [];

    CONST REQ_RPC_TYPE = 8;
    CONST REC_RPC_TYPE = 9;

    static $tacker_obj = false;
    static $common_config = []; //一般配置，目前是选择的协议
    static $send_ip; //发送数据包的地址

    
    //for sdk tracker do not use this
    public static function getModuleIdFromCache($serviceName)
    {
        if (!isset(\StatsCenter::$cliModuleId[$serviceName])) {
            $moduleId = \StatsCenter::getModuleId($serviceName, false);
            \StatsCenter::$cliModuleId[$serviceName] = $moduleId;
        } else {
            $moduleId = \StatsCenter::$cliModuleId[$serviceName];
        }
        return $moduleId;
    }

    /**
     * @param  $func eg.  App\Login\Weibo::login'
     * @param  $serviceName 必须和创建应用时候服务名一致 eg. 'user'
     * @param  $serverIp eg. '192.1.1.1'
     * @return StatsCenter_Tick object
     */
    public static function beforeReqRpc($func, $serviceName, $serverIp)
    {

        $key = $serviceName . "-service:" . $func . "|$serviceName|" . self::REQ_RPC_TYPE;
        $tick = StatsCenter::tick($key, self::getModuleIdFromCache($serviceName));
        $tick->serverIP = $serverIp;
        return $tick;
    }

    /*
     * @param $tick  StatsCenter_Tick object
     * @param $ret   true/false
     * @param $errno 201
     * @return void
     */

    public static function afterReqRpc(StatsCenter_Tick $tick, $ret, $errno)
    {
        $tick->report($ret, $errno, $tick->serverIP);
    }

    /**
     * @param $func eg.  App\Login\Weibo::login'
     * @param $serviceName 必须和创建应用时候服务名一致 eg. 'user'
     * @param $serverIp
     * @param string $traceId
     * @param string $spanId
     * @return StatsCenter_Tick
     * @throws Exception
     */
    public static function beforeExecRpc($func, $serviceName, $serverIp, $traceId = "", $spanId = "")
    {
        apm_set_service_name($serviceName);
        $key = $serviceName . "-service:" . $func . "|0|" . self::REC_RPC_TYPE;

        $moduleId = self::getModuleIdFromCache($serviceName);
        $tick = StatsCenter::tick($key, $moduleId);
        $tick->serverIP = $serverIp;
        $tick->fname = $func;

        if (!self::$tacker_obj) {
            self::$tacker_obj = new \APM();
        }
        self::$tacker_obj->setModuleId($moduleId);
        self::$tacker_obj->cliStartTrace($traceId, $spanId);
        return $tick;
    }

    /*
     * @param $tick  StatsCenter_Tick object
     * @param $ret   true/false
     * @param $errno 201
     * @return void
     */

    public static function afterExecRpc(StatsCenter_Tick $tick, $ret, $errno)
    {
        $tick->report($ret, $errno, $tick->serverIP);
        self::$tacker_obj->cliEndTrace($tick->module_id, $tick->interface_id, $tick->start_ms * 10000, $tick->fname, (string) $errno);
    }

    static function getDefaultNetSvrIP()
    {
        $key = array_rand(self::$default_aop_svr_ips, 1);
        return self::$default_aop_svr_ips[$key];
    }

    static function getNetSvrIP()
    {
        $env = get_cfg_var('env.name');
        $env = $env ?: 'product';
        $key = self::$aop_svr_key;
        $configFile = CloudConfig::CONFIG_PATH . "/{$key}.conf";
        //如果有文件配置下发 使用文件配置
        if (is_file($configFile)) {
            $conf = CloudConfig::get($key, $env);
            if (!empty($conf)) {
                $server = CloudConfig::getServer($conf['servers']);
                if (!empty($server)) {
                    return $server['host'];
                }
            }
        } else {
            return self::getConfigIp($env);
        }
        return self::getDefaultNetSvrIP();
    }

    public static function getConfigIp($env)
    {
        $config_file = __DIR__ . "/config_ip.conf";
        if (is_file($config_file)) {
            $data = json_decode(file_get_contents($config_file), 1);
            if (isset($data['ip'][$env])) {
                return $data['ip'][$env];
            } else {
                return current($data['ip']);
            }
        }
        return false;
    }

    //* $auto_create_module在通过域名获取不到模块id的时候，是否自动在swoole admin创建模块，只有提供服务的fpm调用这个方法的时候需要自动创建
    static function getModuleId($module_key, $auto_create_module = true)
    {
        if (!self::$enable) {
            return self::INVALID_ID;
        }
        $key = str_replace(' ', '_', $module_key);

        $create_module = false;
        if ($auto_create_module && in_array(php_sapi_name(), ["fpm-fcgi", "apache", "cli-server"])) {
            $create_module = true;
        }

        if (file_exists(CloudConfig::CONFIG_PATH . "/userid")) {
            $user_id = (int) file_get_contents(CloudConfig::CONFIG_PATH . "/userid");
            $recv = CloudConfig::getAopConfig([
                        'key' => urlencode($key),
                        'auto_create_module' => $create_module,
                        'user_id' => $user_id
                            ], 'module');
        } else {
            $recv = CloudConfig::getAopConfig([
                        'key' => urlencode($key),
                        'auto_create_module' => $create_module
                            ], 'module');
        }

        $recv = json_decode($recv, 1);
        if (!empty($recv) and isset($recv['code'])) {
            $new_id = (int) $recv['id'];
            return $new_id;
        } else {
            return self::INVALID_ID;
        }
    }

    /*
     * Core.php 的getInterfaceId 会调用这个函数
     * self::getInterfaceId 也会调用这个函数
     */

    static function getInterfaceId($interface_key, $module)
    {
        if (!self::$enable) {
            return self::INVALID_ID;
        }
        $key = str_replace(' ', '_', $interface_key);
        if (file_exists(CloudConfig::CONFIG_PATH . "/userid")) {
            $user_id = (int) file_get_contents(CloudConfig::CONFIG_PATH . "/userid");
            $recv = CloudConfig::getAopConfig([
                        'key' => urlencode($key),
                        'module_id' => $module,
                        'user_id' => $user_id
                            ], 'interface');
        } else {
            $recv = CloudConfig::getAopConfig([
                        'key' => urlencode($key),
                        'module_id' => $module,
                            ], 'interface');
        }

//var_dump($recv,$interface_key);
        $recv = json_decode($recv, 1);
        if (empty($recv) or empty($recv['code'])) {
            $new_id = (int) $recv['id'];
            return $new_id;
        } else {
            return self::INVALID_ID;
        }
    }

    /**
     * 模调手动打点
     * @param $interface
     * @param $module
     * @return StatsCenter_Tick
     * @throws Exception
     */
    static function tick($interface, $module_id)
    {
        $env = get_cfg_var('env.name');
        if (in_array($env, array('test'))) {
            self::$enable = false;
        }
        if (empty($interface)) {
            throw new Exception("interface empty.");
        }
        if (!is_numeric($interface)) {
            $interface = self::getInterfaceId($interface, $module_id);
        }
        $obj = new StatsCenter_Tick($interface, $module_id, self::$round_index);
        self::$round_index++;
        return $obj;
    }

    /**
     * @param $interfaceId
     * @param $moduleId
     * @param $use_ms
     * @param $success
     * @param $code
     * @param $serverIp
     * @param $is_last
     * @return bool
     */
    static function report($interfaceId, $moduleId, $start_time, $success, $code, $serverIp, $is_last = 0)
    {
        //这是一个错误的上报，忽略
        if ($interfaceId == self::INVALID_ID) {
            return false;
        }

        //获取配置目前选择的协议
        self::getConfig();
        $start_time_real = intval($start_time * 1000);
        if (strtoupper(self::$common_config['protocol']) == 'UDP') {
            return self::sendWithUdp($interfaceId, $moduleId, $start_time_real, $success, $code, $serverIp);
        } elseif (strtoupper(self::$common_config['protocol']) == 'TCP') {
            return self::sendWithFile($interfaceId, $moduleId, $start_time_real, $success, $code, $serverIp);
        } else {
            return false;
        }
    }

    /*
     * 当前进程第一次report数据 按照服务端分配缓存区
     */

    static function checkIps()
    {
        if (empty(self::$tmpData)) {
            self::getScSvrIps();
            foreach (self::$sc_svr_ip as $ip) {
                self::$tmpData[$ip] = '';
            }
        }
        return true;
    }

    static function appendData($interface_id, $pkg)
    {
        self::checkIps();
        $k = $interface_id % self::$sc_svr_count;
        $ip = self::$sc_svr_ip[$k];
        self::$tmpData[$ip] .= $pkg;
        return true;
    }

    static function getScSvrIps()
    {
        $env = get_cfg_var('env.name');
        $env = $env ?: 'product';
        $key = self::$sc_svr_key;
        $configFile = CloudConfig::CONFIG_PATH . "/{$key}.conf";
        if (is_file($configFile)) {
            $conf = CloudConfig::get($key, $env);
            if (!empty($conf)) {
                $servers = $conf['servers'];
                foreach ($servers as $k => $svr) {
                    if ($svr['status'] == 'online') {
                        $ret[] = $svr['host'];
                    }
                }
                $ips = array_unique($ret);
                self::$sc_svr_count = count($ips);
                self::$sc_svr_ip = $ips;
                return $ips;
            }
        } else {
            $ip = self::getConfigIp($env);
            self::$sc_svr_ip = [$ip];
        }
        return self::$sc_svr_ip;
    }

    /**
     * 发送UDP数据包
     * @param $data
     */
    protected static function sendDgramPacket($data, $svr_ip)
    {
        if (self::$enable) {
            //创建socket
            if (!isset(self::$socket[$svr_ip])) {
                self::$socket[$svr_ip] = stream_socket_client('udp://' . $svr_ip . ':' . self::UDP_PORT_STATS, $errno, $errstr, 1, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT);
            }
            $ret = stream_socket_sendto(self::$socket[$svr_ip], $data);
            if (empty($ret)) {
                fclose(self::$socket[$svr_ip]);
            }
        }
    }

    /**
     * getConfig 获取配置文件
     * @return bool|mixed
     */
    static public function getConfig()
    {
        if (self::$common_config)
            return self::$common_config;

        $config_file = __DIR__ . "/config_common.conf";
        if (is_file($config_file)) {
            self::$common_config = json_decode(file_get_contents($config_file), true);
            return true;
        }
        return false;
    }

    /**
     * getSendIp 获取udp发送的ip
     */
    static public function getSendIp()
    {
        if (!self::$send_ip) {
            $env = get_cfg_var('env.name') ?: 'product';
            self::$send_ip = self::getConfigIp($env);
        }
    }

    /**
     * sendWithFile 将数据记录文件供node—agent调用用tcp发送
     * @param $interfaceId
     * @param $moduleId
     * @param $use_ms
     * @param $success
     * @param $code
     * @param $serverIp
     * @return bool
     */
    static public function sendWithFile($interfaceId, $moduleId, $start_ms, $success, $code, $serverIp)
    {
        $ip = is_numeric($serverIp) ? $serverIp : ip2long($serverIp);
        apm_tick($interfaceId, $moduleId, $start_ms, $success, $code, $ip);
//        $statsDir = '/tmp/swoole/stats';
//        $ip = is_numeric($serverIp) ? $serverIp : ip2long($serverIp);
//        //写入文件
//        $statsArr = array(
//            "interface_id" => $interfaceId,
//            "module_id" => $moduleId,
//            "success" => $success,
//            "ret_code" => $code,
//            "server_ip" => $ip,
//            "use_ms" => $use_ms,
//            "time" => time(),
//            "client_ip" => StatsCenter_Tick::getIP()
//        );
//        $data = json_encode($statsArr) . '#swoolestats#';
//        $statsFile = $statsDir . "/stats_" . date("YmdHi", time()); //1分钟一个文件
//        $dir = dirname($statsFile);
//        umask(0);
//        if (!is_dir($dir)) {
//            mkdir($dir, 0777, true);
//        }
//        //检查删除15分钟之前的数据
//        if (!is_file($statsFile)) {
//            $filesArr = array();
//            if (@$handle = opendir($statsDir)) { //注意这里要加一个@，不然会有warning错误提示：）
//                while (($file = readdir($handle)) !== false) {
//                    if ($file != ".." && $file != ".") { //排除根目录；
//                        $filesArr[] = $file;
//                    }
//                }
//                closedir($handle);
//            }
//            if ($filesArr) {
//                foreach ($filesArr as $key => $value) {
//                    $nameArr = explode('_', $value);
//                    if (isset($nameArr[1]) && $nameArr[1] < date("YmdHi", time() - 60 * 15)) {
//                        unlink($statsDir . '/' . $value);
//                    }
//                }
//            }
//        }
//        $fp = fopen($statsFile, 'a+');
//        //打开文件失败
//        if (!$fp) {
//            return false;
//        }
//        $length = strlen($data);
//        for ($written = 0; $written < $length; $written += $fwrite) {
//            $fwrite = fwrite($fp, substr($data, $written));
//            //写文件失败了
//            if ($fwrite === false) {
//                fclose($fp);
//                return false;
//            }
//        }
//        fclose($fp);
//        //必须为777
//        @chmod($statsFile, 0777);
//        return true;
    }

    /**
     * sendWithUdp 使用udp发送数据
     * @param $interfaceId
     * @param $moduleId
     * @param $use_ms
     * @param $success
     * @param $code
     * @param $serverIp
     * @return bool|void
     */
    static public function sendWithUdp($interfaceId, $moduleId, $start_ms, $success, $code, $serverIp)
    {
        self::getSendIp();
        $ip = is_numeric($serverIp) ? $serverIp : ip2long($serverIp);
        $use_ms = intval(microtime(true) * 1000 - $start_ms);

        $pkg = pack(StatsCenter::PACK_STATS, $interfaceId, $moduleId, $success, $code, $ip, $use_ms, time());

        if (self::$send_ip) {
            self::sendDgramPacket($pkg, self::$send_ip);
            return true;
        }
        return false;
    }

}

class StatsCenter_Tick
{

    public $start_ms;
    public $module_id;
    public $interface_id;
    protected $params;
    protected $id;
    protected $_end = false;
    protected $_time_out_pkg = array();
    public $serverIP = 0;

    function __construct($interface_id, $module_id, $id)
    {
        $this->interface_id = $interface_id;
        $this->module_id = $module_id;
        $this->start_ms = microtime(true);
        $this->id = $id;
    }

    function addParam($key, $value)
    {
        $this->params[$key] = $value;
    }

    function report($success, $ret_code = 0, $server_ip = 0)
    {
        //避免重复调用
        if ($this->_end) {
            return;
        }
//        $use_ms = intval((microtime(true) - $this->start_ms) * 1000);
        //发送数据
        StatsCenter::report($this->interface_id, $this->module_id, $this->start_ms, $success, $ret_code, $server_ip);
        //关闭上报
        $this->_end = true;
    }

    function reportSucc($success, $server_ip)
    {
        $this->report($success, 0, $server_ip);
    }

    function reportCode($ret_cod, $server_ip)
    {
        if ($ret_cod === 0) {
            $this->report(StatsCenter::SUCC, $ret_cod, $server_ip);
        } else {
            $this->report(StatsCenter::FAIL, $ret_cod, $server_ip);
        }
    }

    static function getIP()
    {
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) {
            $ip = getenv("HTTP_CLIENT_IP");
        } else {
            if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) {
                $ip = getenv("HTTP_X_FORWARDED_FOR");
            } else {
                if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) {
                    $ip = getenv("REMOTE_ADDR");
                } else {
                    if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) {
                        $ip = $_SERVER['REMOTE_ADDR'];
                    } else {
                        $ip = "0.0.0.0";
                    }
                }
            }
        }
        return $ip;
    }

}
