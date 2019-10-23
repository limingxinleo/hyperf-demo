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
use Hyperf\Socket\Socket;

/**
 * @Process(name="DemoProcess")
 */
class DemoProcess extends AbstractProcess
{
    public $pipeType = SOCK_STREAM;

    protected $running = false;

    public function handle(): void
    {
        $socket = make(Socket::class, [$this->process->exportSocket()]);
        while (true) {
            if ($data = $socket->recv()) {
                var_dump('Recv...', $data);
            }

            var_dump('Do something...');
        }
    }
}
