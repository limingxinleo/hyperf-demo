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
namespace App\Service;

use Hyperf\AsyncQueue\Annotation\AsyncQueueMessage;

class JobService
{
    /**
     * @AsyncQueueMessage(delay=1)
     */
    public function example()
    {
        $res = di()->get(\Redis::class)->keys('*');

        var_dump($res);
    }
}
