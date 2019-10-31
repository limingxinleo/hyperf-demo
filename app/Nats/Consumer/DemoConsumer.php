<?php

declare(strict_types=1);

namespace App\Nats\Consumer;

use Hyperf\Nats\AbstractConsumer;
use Hyperf\Nats\Annotation\Consumer;
use Hyperf\Nats\Message;

/**
 * @Consumer(subject="hyperf.demo", name ="DemoConsumer", nums=1)
 */
class DemoConsumer extends AbstractConsumer
{
    public function consume(Message $payload)
    {
        var_dump(1);
        var_dump($payload->getBody());
    }
}
