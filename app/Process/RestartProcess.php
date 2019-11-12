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

use Hyperf\Contract\ConfigInterface;
use Hyperf\Process\AbstractProcess;
use Hyperf\Process\Annotation\Process;

/**
 * @Process(name="RestartProcess")
 */
class RestartProcess extends AbstractProcess
{
    protected $restartInterval = 1;

    public function handle(): void
    {
        $config = di()->get(ConfigInterface::class);
        echo 'Process.Restart ' . json_encode($config->get('etcd')) . PHP_EOL;

        sleep(10);
        echo 'Process.Restart ' . json_encode($config->get('etcd')) . PHP_EOL;
        // throw new \Exception('asdf');
    }
}
