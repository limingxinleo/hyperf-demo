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

namespace App\Process;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Process\AbstractProcess;
use Hyperf\Process\Annotation\Process;

/**
 * @Process(name="custom_process")
 */
class DemoProcess extends AbstractProcess
{
    public function handle(): void
    {
        while (true) {
            sleep(1);

            if (rand(0, 10) > 9) {
                exit();
            }

            $config = di()->get(ConfigInterface::class);
            echo 'Process.Restart ' . json_encode($config->get('etcd')) . PHP_EOL;
        }
    }
}
