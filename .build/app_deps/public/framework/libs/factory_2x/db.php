<?php
global $php;
$configs = $php->config['db'];
if (empty($configs[$php->factory_key]))
{
    throw new Swoole\Exception\Factory("db->{$php->factory_key} is not found.");
}
$config = $configs[$php->factory_key];

$config['type'] = \Swoole\Database::TYPE_COMYSQL;
if (!empty($config['passwd']))
{
    $config['password'] = $config['passwd'];
    unset($config['passwd']);
}
if (!empty($config['name']))
{
    $config['database'] = $config['name'];
    unset($config['name']);
}

//用于隔离多实例
$config['object_id'] = $php->factory_key;

$db = new Swoole\Database($config);

return $db;
