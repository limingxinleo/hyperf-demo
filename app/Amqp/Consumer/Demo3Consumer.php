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
use Hyperf\DbConnection\Db;

/**
 * @Consumer(exchange="hyperf", routingKey="hyperf4", queue="hyperf", nums=1)
 */
class Demo3Consumer extends ConsumerMessage
{
    public function consume($data): string
    {
        Db::beginTransaction();
        throw new \Exception('xxx');
        return Result::ACK;
    }
}
