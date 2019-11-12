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

use App\Service\RedisService;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\Redis\Redis;

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

    public function scan()
    {
        /** @var Redis $redis */
        $redis = di()->get(\Redis::class);

        $iterator = null;
        $result = [];
        $count = 0;
        while (true) {
            $keys = $redis->scan($iterator, '*', 2);
            $result = array_merge($result, $keys);

            if (empty($iterator) || ($count++ > 10)) {
                break;
            }
        }

        return $this->response->success($result);
    }

    public function call()
    {
        $id = 1;

        // di()->get(RedisService::class)->test($id);
        di()->get(RedisService::class)->noCallTest($id);

        // di()->get(RedisService::class)->callTest($id);

        return $this->response->success($id);
    }
}
