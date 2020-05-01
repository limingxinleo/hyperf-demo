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

/**
 * @Consumer(exchange="hyperf", routingKey="confirm", queue="confirm", name="ConfirmConsumer", nums=1, enable=false)
 */
class ConfirmConsumer extends ConsumerMessage
{
    public function consume($data): string
    {
        var_dump('begin');
        sleep(5);
        var_dump('end');
        return Result::ACK;
    }
}
