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
use Hyperf\Contract\OnReceiveInterface;
use Swoole\Server as SwooleServer;

class TcpOnConnectController implements OnReceiveInterface, OnCloseInterface
{
    /**
     * @var array
     */
    protected $data = [];

    protected $listen;

    public function onConnect(SwooleServer $server, int $fd, int $reactorId)
    {
        $this->data[$fd] = '';
    }

    public function onReceive(SwooleServer $server, int $fd, int $fromId, string $data): void
    {
        $this->data[$fd] .= $data;
    }

    public function onClose(SwooleServer $server, int $fd, int $reactorId): void
    {
        var_dump($this->data[$fd], count($this->data));
        unset($this->data[$fd]);
    }
}
