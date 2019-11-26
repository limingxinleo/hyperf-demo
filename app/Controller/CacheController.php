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

use App\Service\Cache\DemoService;
use Hyperf\Cache\Listener\DeleteListenerEvent;
use Hyperf\HttpServer\Annotation\AutoController;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\SimpleCache\CacheInterface;

/**
 * @AutoController(prefix="cache")
 */
class CacheController extends Controller
{
    public function get()
    {
        return $this->response->success(di()->get(CacheInterface::class)->get('key'));
    }

    public function set()
    {
        return $this->response->success(di()->get(CacheInterface::class)->set('key', uniqid()));
    }

    public function testGet()
    {
        $data = di()->get(DemoService::class)->getCache(1);

        return $this->response->success($data);
    }

    public function testDel()
    {
        di()->get(EventDispatcherInterface::class)->dispatch(new DeleteListenerEvent('DemoServiceDelete', ['id' => 1]));

        $data = di()->get(DemoService::class)->getCache(1);

        return $this->response->success($data);
    }
}
