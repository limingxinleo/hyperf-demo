<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Contract\OnMessageInterface;
use Swoole\Server;
use Swoole\Websocket\Frame;
use Swoole\WebSocket\Server as WsServer;

class WebSocketController
{
    /**
     * @param WsServer $server
     * @param Frame $frame
     */
    public function one(Server $server, Frame $frame)
    {
        $server->push($frame->fd, 'FROM1: ' . $frame->data);
    }

    public function two(Server $server, Frame $frame)
    {
        $server->push($frame->fd, 'FROM2: ' . $frame->data);
    }
}
