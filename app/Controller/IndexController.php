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

use App\Client\GrpcClient;
use Hyperf\HttpServer\Annotation\AutoController;

/**
 * Class IndexController.
 * @AutoController
 */
class IndexController extends Controller
{
    public function index()
    {
        $user = $this->request->input('user', 'Hyperf');
        $method = $this->request->getMethod();
        return $this->response->success([
            'user' => $user,
            'method' => $method,
            'message' => 'Hello Hyperf.',
        ]);
    }

    public function send()
    {
        $client = new GrpcClient('127.0.0.1:9502', [
            'credentials' => null,
        ]);

        $request = new \Grpc\HiUser();
        $request->setName(uniqid());
        $request->setSex(1);

        /*
         * @var \Grpc\HiReply
         */
        [$reply, $status] = $client->sayHello($request);

        return $this->response->success([
            $reply->getUser()->getName(),
            $reply->getMessage(),
            $status,
        ]);
    }

    public function send2()
    {
        $client = new GrpcClient('127.0.0.1:9502', [
            'credentials' => null,
        ]);

        $request = new \Grpc\HiUser();
        $request->setName(uniqid());
        $request->setSex(0);

        /*
         * @var \Grpc\HiReply
         */
        [$reply, $status] = $client->sayHello($request);
        if ($status !== 0) {
            return $this->response->fail($status, $reply);
        }

        return $this->response->success([
            $reply->getUser()->getName(),
            $reply->getMessage(),
            $status,
        ]);
    }

    public function send3()
    {
        $client = new GrpcClient('127.0.0.1:9502', [
            'credentials' => null,
        ]);

        $users = [
            ['limx', 1],
            ['hyperf', 1],
            ['asdf', 1],
        ];

        $result = [];
        foreach ($users as [$name, $sex]) {
            $request = new \Grpc\HiUser();
            $request->setName($name);
            $request->setSex($sex);

            /*
             * @var \Grpc\HiReply
             */
            [$reply,] = $client->sayHello($request);
            $result[] = $reply->getUser()->getName();
        }

        return $this->response->success($result);
    }
}
