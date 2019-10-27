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

use Hyperf\AsyncQueue\Job;
use Hyperf\Contract\ConfigInterface;

class ConfigCenterJob extends Job
{
    public function handle()
    {
        $config = di()->get(ConfigInterface::class);

        echo 'Process.Handle ' . json_encode($config->get('etcd')) . PHP_EOL;
    }
}
