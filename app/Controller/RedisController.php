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

/**
 * @AutoController
 */
class RedisController extends Controller
{
    public function pipeline()
    {
        $redis = di()->get(\Redis::class);

        for ($i = 0; $i < 100; ++$i) {
            $pipe = $redis->multi(\Redis::PIPELINE);
            $pipe->set('xxx', uniqid());
            $pipe->set('xxx', uniqid());
            $pipe->exec();
        }

        return $this->response->success('Hello Hyperf!');
    }

    public function index()
    {
        $result = di()->get(\Redis::class)->keys('*');

        return $this->response->success($result);
    }
}
