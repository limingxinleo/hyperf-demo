<?php

use Swoole\Process;

$process = new Process(function (Process $process) {
    $socket = $process->exportSocket();
    var_dump('recv');
    $head = $socket->recvAll(4,1);
    var_dump($head);
    $len = unpack('Nlen', $head)['len'];
    var_dump($len);
    $data = $socket->recvAll($len,1);
    var_dump($data);

    $socket->send('xxxx');
}, false, 1, true);

$process->start();

Swoole\Coroutine\Run(function () use ($process) {
    $socket = $process->exportSocket();
    $data = 'Hello World.';
    $socket->sendAll(pack('N', strlen($data)) . $data);
    $socket->sendAll(pack('N', strlen($data)) . $data);
    var_dump('send');
    $socket->recv();
});