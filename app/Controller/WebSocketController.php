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

namespace App\Controller;

use Hyperf\Contract\OnCloseInterface;
use Hyperf\Contract\OnMessageInterface;
use Hyperf\Contract\OnOpenInterface;
use Swoole\Http\Request;
use Swoole\Server;
use Swoole\Websocket\Frame;
use Swoole\WebSocket\Server as WsServer;

class WebSocketController implements OnMessageInterface, OnOpenInterface, OnCloseInterface
{
    public function onMessage(WsServer $server, Frame $frame): void
    {
        var_dump($frame->data);
        $server->push($frame->fd, 'FROM1: ' . $frame->data);
    }

    public function onClose(Server $server, int $fd, int $reactorId): void
    {
        var_dump('closed');
        $server->push($fd, 'closed');
    }

    public function onOpen(WsServer $server, Request $request): void
    {
        var_dump('opened', $server instanceof \Swoole\WebSocket\Server);
        $server->push($request->fd, 'opened');
    }
}
