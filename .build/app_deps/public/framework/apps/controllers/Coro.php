<?php
namespace App\Controller;
use Swoole;

class Coro extends Swoole\Controller
{
    function rpc()
    {
        $client = Swoole\Coroutine\RPC::getInstance();
        $client->putEnv('app', 'test');
        $client->putEnv('appKey', 'test1234');
        $client->auth('chelun', 'chelun@123456');
        $client->addServers(array('host' => '127.0.0.1', 'port' => 8889));

        $s = microtime(true);
        $ok = $err = 0;
        for ($i = 0; $i < 1; $i++)
        {
            $ret1 = $client->task("BL\\Test::hello");
            $ret2 = $client->task("BL\\Test::test1", ["hello{$i}_2"]);
            $ret3 = $client->task("BL\\Test::test1", ["hello{$i}_3"]);
        }
        echo "use " . (microtime(true) - $s) * 1000, "ms\n";
        var_dump($ret1->getResult(), $ret2->getResult(), $ret3->getResult());
        var_dump($ret1->code, $ret2->code, $ret3->code);
        return "xxx";
    }

    function redis()
    {
        $value = $this->redis->get("key");
        Swoole\Coroutine::sleep(0.1);
        $value2 = $this->redis->get("key_hello");
        return $this->json(['value' => $value, 'value2' => $value2]);
    }
}