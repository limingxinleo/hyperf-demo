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
use Hyperf\Amqp\Message\Type;
use Hyperf\Amqp\Result;

/**
 * @Consumer(exchange="issue1032", routingKey="issue1032", queue="issue1032", name="Issue1032Consumer", nums=1)
 */
class Issue1032Consumer extends ConsumerMessage
{
    protected $type = Type::DIRECT;

    public function consume($data): string
    {
        return Result::ACK;
    }
}
