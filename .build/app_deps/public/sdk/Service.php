<?php
if (!class_exists('Swoole', false))
{
    require_once '/opt/swoole/public/framework/libs/Swoole/Loader.php';
    Swoole\Loader::addNameSpace('Swoole', '/opt/swoole/public/framework/libs/Swoole');
    spl_autoload_register('\\Swoole\\Loader::autoload', true, true);
}

if (defined("ENABLE_SF_CO"))
{
    //必须在Swoole\Client\SOA前开启
    Swoole\Core::$enableCoroutine = true;
}

require_once __DIR__.'/StatsCenter.php';
require_once __DIR__.'/CloudConfig.php';

class Service extends Swoole\Client\SOA
{

    protected $service_name;
    protected $namespace;
    protected $config;

    /**
     * 是否重新加载配置
     */
    protected $reloadConfig = false;

    /**
     * 模调上报的ID
     * @var int
     */
    protected $moduleId = 0;

    const ERR_NO_CONF = 7001;
    const ERR_NO_IP   = 7002;
    const ERR_NO_MODULE_ID   = 7003;

    /**
     * 构造函数
     * @param $service
     * @throws ServiceException
     */
    function __construct($service = 'apm')
    {
        if (empty($service))
        {
            $service = 'apm';
        }
        $this->service_name = strtolower($service);
        $this->parseConfig($this->service_name);

        if (PHP_SAPI == 'cli')
        {
            $this->reloadConfig = true;
        }
        parent::__construct($service);
    }

    private function parseConfig($service)
    {
        $env = get_cfg_var('env.name');
        $env = $env ?: 'product';
        $conf = CloudConfig::get('service:' . $service, $env);
        if (empty($conf))
        {
            throw new ServiceException("get config [{$this->service_name}] failed.", self::ERR_NO_CONF);
        }

        //新版本支持权重和offline
        if (defined('Swoole\Client\SOA::VERSION') and constant('Swoole\Client\SOA::VERSION') > 1000)
        {
            $this->setServers($conf['servers']);
        }
        else
        {
            $iplist = array();
            foreach ($conf['servers'] as $k => $svr)
            {
                if ($svr['status'] == 'online')
                {
                    $iplist[] = $svr['host'] . ':' . $svr['port'];
                }
            }
            if (empty($iplist))
            {
                throw new ServiceException("service{$this->service_name}] ip list is empty.", self::ERR_NO_IP);
            }
            $servers = $iplist;
            $this->setServers($servers);
        }
        $this->config = $conf;
        $this->namespace = @$conf['namespace'];
        //模调系统的ID
        if (empty($conf['module_id']))
        {
            throw new ServiceException("service{$this->service_name}] module_id is empty.", self::ERR_NO_MODULE_ID);
        }
        $this->moduleId = intval($conf['module_id']);
    }

      /**
     * @param $obj Swoole\Client\SOA_Result
     */
    protected function beforeRequest($obj)
    {
        $tick = StatsCenter::beforeReqRpc($obj->send['call'], $this->service_name, $obj->server_host);
        $obj->report_tick = $tick;
    }

    /**
     * @param $obj Swoole\Client\SOA_Result
     */
    protected function afterRequest($obj)
    {
        /**
         * @var $tick StatsCenter_Tick
         */
        StatsCenter::afterReqRpc($obj->report_tick, !empty($obj->data), $obj->code);
    }

    function call()
    {
        if ($this->reloadConfig)
        {
            $this->parseConfig($this->service_name);
        }
        $args = func_get_args();
        return $this->task($this->namespace . '\\' . $args[0], array_slice($args, 1));
    }

    function wait($timeout = 0.5)
    {
        parent::wait($timeout);
    }

}

class ServiceException extends Exception
{

}
