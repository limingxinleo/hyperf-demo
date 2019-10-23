<?php

declare(strict_types=1);

namespace App\Process;

use Hyperf\Process\AbstractProcess;
use Hyperf\Process\Annotation\Process;
use Hyperf\Socket\Socket;

/**
 * @Process(name="DemoProcess")
 */
class DemoProcess extends AbstractProcess
{
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
