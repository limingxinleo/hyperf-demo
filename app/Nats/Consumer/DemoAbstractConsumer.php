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
use Nats\Message;

/**
 * @Consumer(name="DemoAbstractConsumer", subject="hyperf.demo")
 */
class DemoAbstractConsumer extends AbstractConsumer
{
    public function handle(Message $payload)
    {
        var_dump($payload->getBody());
    }
}
