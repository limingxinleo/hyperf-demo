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

namespace App\Amqp\Producer;

use Hyperf\Amqp\Annotation\Producer;
use Hyperf\Amqp\Message\ProducerMessage;

/**
 * @Producer(exchange="hyperf", routingKey="large")
 */
class LargeProducer extends ProducerMessage
{
    public function __construct($data)
    {
        $this->payload = [
            'name' => 'Hyperf',
            'data' => $data,
            'is' => $data,
        ];
    }
}
