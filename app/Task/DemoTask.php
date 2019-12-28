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

namespace App\Task;

use Hyperf\Utils\Coroutine;

class DemoTask
{
    public function handle($id)
    {
        return [
            'server.cid' => $id,
            'task.cid' => Coroutine::id(),
        ];
    }
}
