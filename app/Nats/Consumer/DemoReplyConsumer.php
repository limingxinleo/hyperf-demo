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
 * @Consumer(name="DemoReplyConsumer", subject="hyperf.reply")
 */
class DemoReplyConsumer extends AbstractConsumer
{
    public function handle(Message $payload)
    {
        var_dump($payload->getBody());

        $payload->reply('Hello Nats');
    }
}
