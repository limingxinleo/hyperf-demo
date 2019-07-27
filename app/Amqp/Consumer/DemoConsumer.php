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

namespace App\Amqp\Consumer;

use Hyperf\Amqp\Annotation\Consumer;
use Hyperf\Amqp\Message\ConsumerMessage;
use Hyperf\Amqp\Result;

/**
 * @Consumer(exchange="hyperf", routingKey={"hyperf","hyperf2"}, queue="hyperf", nums=1)
 */
class DemoConsumer extends ConsumerMessage
{
    // protected $routingKey = [
    //     'hyperf',
    //     'hyperf2'
    // ];

    public function consume($data): string
    {
        var_dump($data);
        return Result::ACK;
    }
}
