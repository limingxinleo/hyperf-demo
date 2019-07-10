<?php
define('DEBUG', 'on');
define('WEBPATH', realpath(__DIR__ . '/..'));
//包含框架入口文件
require WEBPATH . '/libs/lib_config.php';

$mc = new Swoole\Coroutine\Memcache();
$mc->addServer('127.0.0.1', 11211);

Swoole\Coroutine::create(function () use ($mc) {
    echo "Memcached\n";
//    var_dump(Swoole::$php->cache->set('key', 'value', 3000));
//    var_dump(Swoole::$php->cache->set('rango', 'hello', 3000));
//    var_dump(Swoole::$php->cache->add('counter', 0));
//    var_dump(Swoole::$php->cache->get('rango'));
//        var_dump(Swoole::$php->cache->increment('counter', 10));
//    var_dump(Swoole::$php->cache->getMulti(['world5', 'world4']));
//    var_dump(Swoole::$php->cache->getStats());
//    return;

    echo "Coro\Memcached\n";
//    $value = $mc->add('world5', 'php', 0);
//    var_dump($mc->set('world5', 'java', 9999));
//    var_dump($mc->getMulti(['world5', 'world4']));
//    var_dump($mc->getStats());
//    var_dump($mc->delete('key'));
   // $value = $mc->set("key", 'value', 3000);
//    var_dump($mc->increment('counter', 10));
//    var_dump($mc->decrement('counter', 10));
    var_dump($mc->replace('rango', 'swoole'));
});