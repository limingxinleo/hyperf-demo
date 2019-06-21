<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Contract\OnMessageInterface;
use Swoole\Server;
use Swoole\Websocket\Frame;

class WebSocketController implements OnMessageInterface
{
    public function ws(RequestInterface $request, ResponseInterface $response)
    {
        return $response->raw('Hello Hyperf!');
    }

    public function onMessage(Server $server, Frame $frame): void
    {
        var_dump(111);
    }
}
