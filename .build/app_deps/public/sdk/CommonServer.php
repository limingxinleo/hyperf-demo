<?php

if (!class_exists('Swoole', false)) {
    require_once '/opt/swoole/public/framework/libs/Swoole/Loader.php';
    Swoole\Loader::addNameSpace('Swoole', '/opt/swoole/public/framework/libs/Swoole');
    spl_autoload_register('\\Swoole\\Loader::autoload', true, true);
}

require_once __DIR__ . '/StatsCenter.php';
require_once __DIR__ . '/CloudConfig.php';

class CommonServer extends Swoole\Protocol\SOAServer
{

    CONST SVR_TYPE = 9;

    public $haveTracker = false;
    public $apm = false;
    public $client;
    public $moduleId = 0;
    public $service_name = 'test';

    function setServiceName($name)
    {
        $this->service_name = strtolower($name);
        $this->parseConfig($this->service_name);
    }

    private function parseConfig($service)
    {
        $env = get_cfg_var('env.name');
        $env = $env ?: 'product';
        $conf = CloudConfig::get('service:' . $service, $env);
        if (empty($conf)) {
            throw new \Exception("get config [{$this->service_name}] failed.", 0);
        }
        //模调系统的ID
        if (empty($conf['module_id'])) {
            throw new \Exception("service config module_id empty", 0);
        }
        $this->moduleId = intval($conf['module_id']);
    }

    function onStart($server)
    {
//        if (empty($this->moduleId) or empty($this->service_name)) {
//            exit("please call setServiceName to set valid service name.\n");
//        }
//        $this->haveTracker = extension_loaded('swoole_tracker');
//        if ($this->haveTracker) {
//            apm_set_service_name($this->service_name . '-service');
//            $this->apm = new \APM();
//            $this->apm->setModuleId($this->moduleId);
//            \StatsCenter::$cliModuleId = $this->moduleId;
//        }
    }

    function getKey($key)
    {
        return $this->service_name . "-service:Backend:" . $key . "|0|" . self::SVR_TYPE;
    }

//    function call($request, $header)
//    {
//        $key = $this->getKey($request['call']);
//        $clientEnv = Swoole\Protocol\RPCServer::getClientEnv();
//        $remote_ip = 0;
//        if (!empty($clientEnv['_socket'])) {
//            $remote_ip = $clientEnv['_socket']['remote_ip'];
//        }
//
//        // 模调上报
//        $tick = \StatsCenter::tick($key, $this->moduleId);
//        try
//        {
//            if ($this->haveTracker) {
//                if (isset($request['env']['trace_id']) and isset($request['env']['span_id'])) {
//                    $this->apm->cliStartTrace($request['env']['trace_id'], $request['env']['span_id']);
//                } else {
//                    $this->apm->cliStartTrace();
//                }
//                Sdk\Trace::start();
//            }
//
////            var_dump(\Co::getuid());
////            co::sleep(10);
//            $ret = parent::call($request, $header);
//            if ($ret['errno'] != 0)
//            {
//                $tick->report(false, $ret['errno'], $remote_ip);
//            }
//            else
//            {
//                $tick->report(true, 0, $remote_ip);
//            }
//            if ($this->haveTracker) {
//                $this->apm->cliEndTrace();
//                Sdk\Trace::end();
//            }
//            return $ret;
//        }
//        catch (\Exception $e)
//        {
//            $tick->report(false, $e->getCode(), $remote_ip);
//            throw $e;
//        }
//    }



    function call($request, $header)
    {
        $tmp = swoole_get_local_ip(); // 获取第一个
        $trace_id = isset($request['env']['trace_id']) ?? "";
        $span_id = isset($request['env']['span_id']) ?? "";
        $tick = \StatsCenter::beforeExecRpc($request['call'], $this->service_name, array_shift($tmp), $trace_id,$span_id);
        try {
//            if ($this->haveTracker) {
//                if (isset($request['env']['trace_id']) and isset($request['env']['span_id'])) {
//                    $this->apm->cliStartTrace($request['env']['trace_id'], $request['env']['span_id']);
//                } else {
//                    $this->apm->cliStartTrace();
//                }
//            }

            $ret = parent::call($request, $header);
            if ($ret['errno'] != 0) {
                $success = 0;
            } else {
                $success = 1;
            }
            \StatsCenter::afterExecRpc($tick, $success, $ret['errno']);

            return $ret;
        } catch (\Exception $e) {
            $tick->report(false, $e->getCode(), array_shift($tmp));
            throw $e;
        }
    }

}
