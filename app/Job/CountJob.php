<?php

declare(strict_types=1);

namespace App\Job;

use Hyperf\AsyncQueue\Job;

class CountJob extends Job
{
    public function handle()
    {
        di()->get(\Redis::class)->incr('count');
        di()->get(\Redis::class)->setnx('begintime', microtime(true));
        di()->get(\Redis::class)->set('endtime', microtime(true));
    }
}
