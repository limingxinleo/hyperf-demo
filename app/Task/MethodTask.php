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

namespace App\Task;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use Hyperf\Task\Annotation\Task;
use Hyperf\Utils\Coroutine;

class MethodTask
{
    /**
     * @Task
     * @param mixed $cid
     */
    public function handle($cid)
    {
        return [
            'worker.cid' => $cid,
            'task.cid' => Coroutine::id(),
        ];
    }

    public function handle2($cid)
    {
        return [
            'worker.cid' => $cid,
            'no.task.cid' => Coroutine::id(),
        ];
    }

    /**
     * @Task(timeout=1)
     */
    public function timeout()
    {
        sleep(2);

        return microtime(true);
    }

    /**
     * @Task
     */
    public function exception()
    {
        throw new BusinessException(ErrorCode::SERVER_ERROR, 'Task Exception');
    }
}
