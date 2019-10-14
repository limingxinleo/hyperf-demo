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

namespace App\Controller;

use App\Amqp\Producer\Demo2Producer;
use App\Amqp\Producer\Demo4Producer;
use App\Amqp\Producer\DemoProducer;
use Hyperf\HttpServer\Annotation\AutoController;

/**
 * @AutoController
 */
class AmqpController extends Controller
{
    public function index()
    {
        $res = amqp_produce(new DemoProducer(uniqid()));
        $res = amqp_produce(new Demo2Producer(uniqid()));
        $res = amqp_produce(new Demo2Producer(uniqid()));
        $res = amqp_produce(new Demo4Producer(uniqid()));

        return $this->response->success($res);
    }

    public function concurrent()
    {
        $res = amqp_produce(new DemoProducer(uniqid()));
        return $this->response->success($res);
    }
}
