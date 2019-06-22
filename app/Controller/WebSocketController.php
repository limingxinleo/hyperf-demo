<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\Contract\OnCloseInterface;
use Hyperf\Contract\OnOpenInterface;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Contract\OnMessageInterface;
use Swoole\Http\Request;
use Swoole\Server;
use Swoole\Websocket\Frame;
use Swoole\WebSocket\Server as WsServer;

class WebSocketController implements OnMessageInterface, OnOpenInterface, OnCloseInterface
{
    public function onMessage(Server $server, Frame $frame): void
    {
        $server->push($frame->fd, 'FROM1: ' . $frame->data);
    }

    public function onClose(Server $server, int $fd, int $reactorId): void
    {
        var_dump('closed');
        $server->push($fd, 'closed');
    }

    public function onOpen(Server $server, Request $request): void
    {
        var_dump('opened',$server instanceof \Swoole\WebSocket\Server);
        $server->push($request->fd, 'opened');
    }
}
