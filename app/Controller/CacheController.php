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
}
