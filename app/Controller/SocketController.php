<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Process\ProcessCollector;
use Hyperf\Process\ProcessManager;
use Hyperf\Socket\Socket;
use Hyperf\Utils\Arr;

/**
 * @AutoController(prefix="socket")
 */
class SocketController extends Controller
{
    public function send()
    {
        $process = ProcessCollector::get('DemoProcess')[0];

        $socket = make(Socket::class, [$process->exportSocket()]);

        $res = $socket->send(['id' => 1]);

        return $this->response->success();
    }
}
