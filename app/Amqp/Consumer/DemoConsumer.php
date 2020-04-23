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

namespace App\Amqp\Consumer;

use Hyperf\Amqp\Annotation\Consumer;
use Hyperf\Amqp\Message\ConsumerMessage;
use Hyperf\Amqp\Result;
use Hyperf\Contract\StdoutLoggerInterface;

/**
 * @Consumer(exchange="hyperf", routingKey={"hyperf", "hyperf2"}, queue="hyperf", nums=1, enable=true)
 */
class DemoConsumer extends ConsumerMessage
{
    public function consume($data): string
    {
        $logger = di()->get(StdoutLoggerInterface::class);
        $logger->info('demo' . json_encode($data));

        $redis = di()->get(\Redis::class);
        $redis->incr('xxx');

        return Result::ACK;
    }
}
