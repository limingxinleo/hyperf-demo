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
 * @Consumer(topic="sample_topic", channel="test", name="NsqConsumer", nums=1)
 */
class NsqConsumer extends AbstractConsumer
{
    public function consume(Message $payload)
    {
        var_dump(get_called_class() . $payload->getBody());
    }
}
