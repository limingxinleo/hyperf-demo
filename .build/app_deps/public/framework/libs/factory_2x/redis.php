<?php
global $php;

$config = $php->config['redis'][$php->factory_key];
if (empty($config) or empty($config['host']))
{
    throw new Exception("require redis[$php->factory_key] config.");
}

if (empty($config['port']))
{
    $config['port'] = 6379;
}

if (empty($config['timeout']))
{
    $config['timeout'] = 0.5;
}

//用于隔离多实例
$config['object_id'] = $php->factory_key;

$redis = new Swoole\Coroutine\Component\Redis($config);
return $redis;