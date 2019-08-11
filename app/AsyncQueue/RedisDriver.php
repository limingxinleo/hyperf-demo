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

namespace App\AsyncQueue;

use Hyperf\Redis\RedisProxy;
use Psr\Container\ContainerInterface;

class RedisDriver extends \Hyperf\AsyncQueue\Driver\RedisDriver
{
    public function __construct(ContainerInterface $container, $config)
    {
        parent::__construct($container, $config);

        $this->redis = make(RedisProxy::class, [
            'pool' => 'queue',
        ]);
    }
}
