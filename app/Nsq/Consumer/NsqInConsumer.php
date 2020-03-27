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

namespace App\Nsq\Consumer;

use Hyperf\Nsq\AbstractConsumer;
use Hyperf\Nsq\Annotation\Consumer;
use Hyperf\Nsq\Message;
use Hyperf\Nsq\Result;

/**
 * @Consumer(topic="test2", channel="test", name="NsqInConsumer", nums=1)
 */
class NsqInConsumer extends AbstractConsumer
{
    public function consume(Message $payload): ?string
    {
        var_dump($payload->getBody());

        return Result::ACK;
    }
}
