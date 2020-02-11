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
 * @Consumer(exchange="hyperf", routingKey="hyperf3", queue="hyperf2", nums=1, enable=false)
 */
class Demo2Consumer extends ConsumerMessage
{
    public function consume($data): string
    {
        $logger = di()->get(StdoutLoggerInterface::class);
        $logger->info('demo2' . json_encode($data));
        return Result::ACK;
    }
}
