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

namespace App\Job;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use Hyperf\AsyncQueue\Job;

class CountJob extends Job
{
    public function handle()
    {
        di()->get(\Redis::class)->incr('count');
        di()->get(\Redis::class)->setnx('begintime', microtime(true));
        di()->get(\Redis::class)->set('endtime', microtime(true));

        if (rand(0, 99) < 10) {
            throw new BusinessException(ErrorCode::SERVER_ERROR);
        }
    }
}
