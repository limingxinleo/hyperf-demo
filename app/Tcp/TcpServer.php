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
namespace App\Tcp;

use Hyperf\Contract\OnReceiveInterface;
use Swoole\Server as SwooleServer;

class TcpServer implements OnReceiveInterface
{
    public function onReceive(SwooleServer $server, int $fd, int $fromId, string $data): void
    {
        $server->send($fd, 'xxxx' . uniqid());
    }
}
