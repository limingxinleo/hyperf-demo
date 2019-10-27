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

namespace App\Process;

use Hyperf\Process\AbstractProcess;
use Hyperf\Process\Annotation\Process;

/**
 * @Process(name="DemoProcess")
 */
class DemoProcess extends AbstractProcess
{
    public function handle(): void
    {
        var_dump(date('Y-m-d H:i:s'));
        throw new \Exception('asdf');
    }
}
