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

use Hyperf\Contract\OnMessageInterface;
use Swoole\Server;
use Swoole\Websocket\Frame;

class WebSocket2Controller implements OnMessageInterface
{
    public function onMessage(Server $server, Frame $frame): void
    {
        $server->push($frame->fd, 'FROM2: ' . $frame->data);
    }
}
