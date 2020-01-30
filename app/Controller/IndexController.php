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

use Swoole\Timer;

class IndexController extends Controller
{
    public function index()
    {
        $user = $this->request->input('user', 'Hyperf');
        $method = $this->request->getMethod();
        return $this->response->success([
            'user' => $user,
            'method' => $method,
            'message' => 'Hello Hyperf.',
            'headers' => $this->request->getHeaders(),
        ]);
    }

    public function user(int $id)
    {
        for ($i = 0; $i < 10; $i++) {
            Timer::tick(1000, function () {
                sleep(3);
            });
        }
        return $this->response->success($id);
    }
}
