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
namespace App\Amqp\RpcMessage;

use Hyperf\Amqp\Message\RpcMessage;

class DemoRpcMessage extends RpcMessage
{
    public $exchange = 'hyperf';

    public $routingKey = 'reply2';

    public function __construct(array $data)
    {
        $this->payload = $data;
    }
}
