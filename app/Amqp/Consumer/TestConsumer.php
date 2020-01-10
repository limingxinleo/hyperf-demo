<?php

declare(strict_types=1);

namespace App\Amqp\Consumer;

use Hyperf\Amqp\Result;
use Hyperf\Amqp\Annotation\Consumer;
use Hyperf\Amqp\Message\ConsumerMessage;

/**
 * @Consumer(exchange="hyperf", routingKey="hyperf", queue="hyperf", name ="TestConsumer", nums=1, enable=false)
 */
class TestConsumer extends ConsumerMessage
{
    public function consume($data): string
    {
        var_dump($data);
        return Result::ACK;
    }
}
