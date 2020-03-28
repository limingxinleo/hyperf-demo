<?php

declare(strict_types=1);

namespace App\Process;

use Hyperf\Process\AbstractProcess;
use Hyperf\Process\Annotation\Process;
use Swoole\Coroutine\Channel;

/**
 * @Process(name="xxxx")
 */
class XXXProcess extends AbstractProcess
{
    protected function listen(Channel $quit)
    {
        //
    }

    public function handle(): void
    {
        $socket = $this->process->exportSocket();
        while (true) {
            // 只要在这里使用阻塞 就会导致错误
            if ($recv = $socket->recv(1)) {
                var_dump($recv);
                sleep(5);
            }
        }
    }
}
