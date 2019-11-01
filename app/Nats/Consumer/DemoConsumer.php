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

namespace App\Nats\Consumer;

use Hyperf\Nats\AbstractConsumer;
use Hyperf\Nats\Annotation\Consumer;
use Hyperf\Nats\Message;

/**
 * @Consumer(subject="hyperf.demo", queue="hyperf.demo", name="DemoConsumer", nums=1)
 */
class DemoConsumer extends AbstractConsumer
{
    public function consume(Message $payload)
    {
        var_dump(1);
        var_dump($payload->getBody());
    }
}
