<?php
define('DEBUG', 'on');
define('WEBPATH', realpath(__DIR__ . '/..'));
//包含框架入口文件
require WEBPATH . '/libs/lib_config.php';

$n = 4;

$array = new \Swoole\ArrayObject();
$array['test1'] = 123;
$array['test2'] = 456;
$array['test3'] = 789;

if ($n == 1)
{
    $array->each(function (&$item, $key)
    {
        var_dump($item, $key);
        $item = rand(100, 999);
    });
}
elseif ($n == 2)
{
    var_dump($array->reverse());
}
elseif ($n == 3)
{
    $newArray = $array->map(function ($item)
    {
        var_dump($item);
        return rand(100, 999);
    });
    var_dump($newArray);
}
elseif ($n == 4)
{
    echo $array->reduce(function ($result, $item)
    {
        return $result + $item;
    });
}


