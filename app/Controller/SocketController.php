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

use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\Process\ProcessCollector;
use Hyperf\Socket\Socket;

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
