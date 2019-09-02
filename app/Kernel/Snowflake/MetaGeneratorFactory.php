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

namespace App\Kernel\Snowflake;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Snowflake\ConfigInterface as SnowflakeConfigInterface;
use Hyperf\Snowflake\MetaGenerator\RedisSecondMetaGenerator;
use Psr\Container\ContainerInterface;

class MetaGeneratorFactory
{
    const DEFAULT_BEGIN_SECOND = 1558368000;

    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get(ConfigInterface::class);
        $beginSecond = $config->get('snowflake.begin_second', self::DEFAULT_BEGIN_SECOND);

        return make(RedisSecondMetaGenerator::class, [
            $config,
            $container->get(SnowflakeConfigInterface::class),
            $beginSecond,
        ]);
    }
}
