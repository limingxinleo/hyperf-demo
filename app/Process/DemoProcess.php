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

use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Process\AbstractProcess;
use Hyperf\Process\Annotation\Process;

/**
 * @Process(name="DemoProcess")
 */
class DemoProcess extends AbstractProcess
{
    // public $nums = 2;

    protected $running = true;

    public function handle(): void
    {
        \Swoole\Process::signal(SIGTERM, function () {
            $this->running = false;
            var_dump('recv...');
        });

        while ($this->running) {
            di()->get(StdoutLoggerInterface::class)->info('sleep...');
            sleep(1);
            di()->get(StdoutLoggerInterface::class)->info('down...');
        }
    }
}
