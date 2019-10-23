<?php

declare(strict_types=1);

/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

use Swoole\Process;

$process = new Process(function (Process $process) {
    $socket = $process->exportSocket();
    $head = $socket->recvAll(4, 1.0);
    var_dump($head);
    $len = unpack('Nlen', $head)['len'];
    var_dump($len);
    $data = $socket->recvAll($len, 1.0);
    var_dump(strlen($data));

    $socket->send('xxxx');
}, false, 1, true);

$process->start();

Swoole\Coroutine\Run(function () use ($process) {
    $socket = $process->exportSocket();
    $data = '';
    for ($i = 0; $i < 70000; $i++) {
        $data .= 'a';
    }
    $socket->sendAll(pack('N', strlen($data)) . $data);
    $socket->recv();
});
