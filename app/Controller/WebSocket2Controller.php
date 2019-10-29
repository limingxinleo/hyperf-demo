<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace App\Controller;

use Hyperf\Contract\OnCloseInterface;
use Hyperf\Contract\OnMessageInterface;
use Hyperf\Contract\OnOpenInterface;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Di\Annotation\Inject;
use Swoole\Http\Request;
use Swoole\Server;
use Swoole\Websocket\Frame;
use Swoole\WebSocket\Server as WebSocketServer;

class WebSocket2Controller implements OnMessageInterface, OnOpenInterface, OnCloseInterface
{
    /**
     * @Inject
     * @var StdoutLoggerInterface
     */
    protected $logger;

    public function onClose(Server $server, int $fd, int $reactorId): void
    {
        $this->logger->info(sprintf('%d closed', $fd));
    }

    public function onMessage(WebSocketServer $server, Frame $frame): void
    {
        $this->logger->info(sprintf('recv from %d, data=%s', $frame->fd, $frame->data));
        $server->push($frame->fd, 'recv: ' . $frame->data);
    }

    public function onOpen(WebSocketServer $server, Request $request): void
    {
        $server->push($request->fd, 'opened');
        var_dump(static::class);
    }
}
