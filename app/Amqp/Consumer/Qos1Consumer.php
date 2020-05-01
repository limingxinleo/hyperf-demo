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
 * @Consumer(exchange="test", routingKey="test.qos", queue="test.qos", name="Qos1Consumer", nums=1, enable=false)
 */
class Qos1Consumer extends ConsumerMessage
{
    protected $qos = [];

    public function consume($data): string
    {
        var_dump('qos1.begin');
        sleep(1);
        var_dump('qos1.end');
        return Result::ACK;
    }
}
