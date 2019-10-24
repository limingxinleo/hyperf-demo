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

namespace App\Job;

use Hyperf\AsyncQueue\Job;
use Hyperf\Contract\StdoutLoggerInterface;

class FailedJob extends Job
{
    protected $maxAttempts = 1;

    public function handle()
    {
        $logger = di()->get(StdoutLoggerInterface::class);
        $logger->info('Handle async queue Failed.');

        throw new \Exception('xxx');
    }
}
