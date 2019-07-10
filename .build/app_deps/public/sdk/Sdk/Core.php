<?php

namespace Sdk;

class Core
{

    static public $module_conf_key = "module/module_%s";

    CONST TOP_LEVEL = 1;

    static private $mysql_method = ['select', 'update', 'delete'];
    static private $mysql_method_len = 6;
    static $debug = 0;
    static $sapi = ["fpm-fcgi", "apache", "cli-server"];

    static function checkSAPI()
    {
        return in_array(php_sapi_name(), self::$sapi);
    }


    static function getWhiteList($module_id)
    {
        $env_from_ini = get_cfg_var('env.name');
        if (empty($env_from_ini)) {
            $env_from_ini = "product";
        }
        //获取系统模块的接口命名的配置
        $key = sprintf(self::$module_conf_key, $module_id);
        $conf = \CloudConfig::get($key, $env_from_ini);
        if (empty($conf)) {
        	file_put_contents("/tmp/swoole/whitelist.log", '[' . date('Y-m-d H:i:s') . '] '. $key .'获取白名单失败'. PHP_EOL, FILE_APPEND);
            return [];
        }
//        return ['Model', 'wp-includes', 'App'];
        return (array)$conf['whitelist'];
    }

//    static function getInterfaceId($module_id, $instance, $req_type, $is_rshutdown = false)
//    {
//        $env_from_ini = get_cfg_var('env.name');
//        if (empty($env_from_ini)) {
//            $env_from_ini = "product";
//        }
//        $interface_name = "";
//        if ($is_rshutdown and self::checkSAPI()) {
//            if (isset($_SERVER['REQUEST_URI'])) {
//                $tmp = parse_url($_SERVER['REQUEST_URI']);
//                $interface_name = $tmp['path'];
//            }
//        } else {
//            //获取系统模块的接口命名的配置
//            $key = sprintf(self::$module_conf_key, $module_id);
//            $conf = \CloudConfig::get($key, $env_from_ini);
//            if (empty($conf)) {
//                goto error;
//            }
//
//            $backtrace = debug_backtrace();
//            $show_line = boolval($conf['show_line']);
//            $whitelist = $conf['whitelist'];
//            //add CommonServer for swoole service auto
//            if (!in_array("CommonServer", $whitelist['class'])) {
//                array_push($whitelist['class'], "CommonServer");
//            }
//            if (!in_array("Service", $whitelist['class'])) {
//                array_push($whitelist['class'], "Service");
//            }
//            $find = [];
//            $call_index = 0;
//            $top = $backtrace[self::TOP_LEVEL];
//            $top_function = isset($top['function']) ? $top['function'] : '';
//            foreach ($backtrace as $k => $b) {
//                foreach ($whitelist as $type => $list) {
//                    foreach ($list as $v) {
//                        //包含通配符 例子 function class 以swoole*开头
//                        if (stripos($v, "*") !== false) {
//                            $target = substr($v, 0, -1);
//                            if (isset($b[$type]) and stripos($b[$type], $target) === 0) {
//                                $find = $b;
//                                $call_index = $k;
//                                break 3;
//                            }
//                        } else {
//                            if (isset($b[$type]) and strtolower($b[$type]) == strtolower($v)) {
//                                $find = $b;
//                                $call_index = $k;
//                                break 3;
//                            }
//                        }
//                    }
//                }
//            }
//            if (empty($find)) {
//                $top_function_no_find = isset($top['function']) ? $top['function'] : '';
//                $top_class_no_find = isset($top['class']) ? $top['class'] : '';
//                if ($top_class_no_find) {
//                    $interface_name = $top_class_no_find . "->" . $top_function_no_find;
//                } else {
//                    $interface_name = $top_function_no_find;
//                }
//            } else {
//                $char = ":";
//                if (isset($find['file'])) {
//                    $interface_name = basename($find['file']);
//                }
//                if ($show_line and isset($find['line'])) {
//                    if ($interface_name) {
//                        $interface_name .= $char . $find['line'];
//                    } else {
//                        $interface_name .= $find['line'];
//                    }
//                }
//                if (isset($find['class'])) {
//                    if ($interface_name) {
//                        $interface_name .= $char . $find['class'];
//                    } else {
//                        $interface_name .= $find['class'];
//                    }
//                }
//                if (isset($find['function'])) {
//                    if (!empty($find['type'])) {
//                        $char = $find['type'];
//                    }
//                    if ($interface_name) {
//                        $interface_name .= $char . $find['function'];
//                    } else {
//                        $interface_name .= $find['function'];
//                    }
//                }
//                if ($top_function != $find['function']) {
//                    $interface_name .= $char . $top_function;
//                }
//                if ($top['function'] == "query") {
//
//                    if (!empty($top['args'][0])) {
//                        $str = substr($top['args'][0], 0, self::$mysql_method_len);
//                        if (in_array($str, self::$mysql_method)) {
//                            $interface_name .= ":" . $str;
//                        }
//                    }
//                }
//            }
//        }
//        $interface_name .= "|$instance|$req_type";
////        var_dump($interface_name,$top_function);
//        $ret = (int) \StatsCenter::getInterfaceId($interface_name, $module_id);
//        if (self::$debug) {
//            $str = "get interface (module_id:$module_id) interface_name:($interface_name) interface_id:($ret)." . var_export($is_rshutdown, 1) . "\n";
//            echo $str;
//        }
//        return $ret;
//        error:
//        return \StatsCenter::INVALID_ID;
//    }
}
