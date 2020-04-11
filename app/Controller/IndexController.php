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

use Swoole\Coroutine\Socket;

class IndexController extends Controller
{
    public function index()
    {
        $socket = new Socket(AF_INET, SOCK_STREAM, 0);
        $socket->connect('127.0.0.1', 9502);
        $socket->send('hello');
        $data = $socket->recv();
        return $this->response->success($data);
    }
}
