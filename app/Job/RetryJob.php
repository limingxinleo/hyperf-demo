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

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use Hyperf\AsyncQueue\Job;

class RetryJob extends Job
{
    protected $maxAttempts = 5;

    public function handle()
    {
        var_dump('retry');

        throw new BusinessException(ErrorCode::SERVER_ERROR);
    }
}
