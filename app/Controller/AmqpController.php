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
namespace App\Controller;

use App\Amqp\Producer\ConfirmProducer;
use App\Amqp\Producer\Demo2Producer;
use App\Amqp\Producer\Demo4Producer;
use App\Amqp\Producer\DemoProducer;
use App\Amqp\Producer\LargeProducer;
use App\Amqp\Producer\QosProducer;
use App\Amqp\Producer\TimeoutProducer;
use App\Amqp\RpcMessage\DemoRpcMessage;
use Hyperf\Amqp\Producer;
use Hyperf\Amqp\RpcClient;
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
        for ($i = 0; $i < 10000; ++$i) {
            $res = amqp_produce(new DemoProducer(uniqid()));
        }
        return $this->response->success($res);
    }

    public function confirm()
    {
        $res = amqp_produce(new ConfirmProducer(uniqid()));

        // $res = di()->get(Producer::class)->produce(new ConfirmProducer(uniqid()), true);

        return $this->response->success($res);
    }

    public function qos()
    {
        amqp_produce(new QosProducer($id = uniqid()));
        return $this->response->success($id);
    }

    public function timeout()
    {
        $time = $this->request->input('time', 1);

        amqp_produce(new TimeoutProducer($time));

        return $this->response->success($time);
    }

    public function large()
    {
        $data = [
            'id' => uniqid(),
            'data' => str_repeat(uniqid(), 1000),
            'requeue' => true,
        ];
        amqp_produce(new LargeProducer($data));
        amqp_produce(new LargeProducer($data));
        amqp_produce(new LargeProducer($data));
        amqp_produce(new LargeProducer($data));
        amqp_produce(new LargeProducer($data));
        return $this->response->success();
    }

    public function request()
    {
        $message = new DemoRpcMessage(['id' => uniqid()]);
        $result = di()->get(RpcClient::class)->call($message);

        var_dump($result);
        return $this->response->success();
    }
}
