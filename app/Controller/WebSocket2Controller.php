<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Contract\OnMessageInterface;
use Swoole\Server;
use Swoole\Websocket\Frame;
use Swoole\WebSocket\Server as WsServer;

class WebSocket2Controller implements OnMessageInterface
{
    public function onMessage(Server $server, Frame $frame): void
    {
        $server->push($frame->fd, 'FROM2: ' . $frame->data);
    }
}
