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

namespace App\Service\Cache;

use Hyperf\Cache\Annotation\Cacheable;

class DemoService
{
    /**
     * @Cacheable(prefix="cache", value="_#{id}", listener="DemoServiceDelete")
     */
    public function getCache(int $id)
    {
        return $id . '_' . uniqid();
    }
}
