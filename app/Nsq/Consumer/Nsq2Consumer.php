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

/**
 * @Consumer(topic="test", channel="test", name="Nsq2Consumer", nums=1)
 */
class Nsq2Consumer extends AbstractConsumer
{
    public function consume(Message $payload): ?string
    {
        var_dump(get_called_class() . $payload->getBody());

        return 'ack';
    }
}
