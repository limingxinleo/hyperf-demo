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

use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\Nats\Driver\DriverFactory;

/**
 * @AutoController(prefix="nats")
 */
class NatsController extends Controller
{
    /**
     * @Inject
     * @var DriverFactory
     */
    protected $factory;

    public function publish()
    {
        $connection = $this->factory->get();
        $res = $connection->publish('hyperf.demo', [
            'id' => 'limx',
        ]);

        return $this->response->success($res);
    }

    public function request()
    {
        $connection = $this->factory->get();
        $res = $connection->request('hyperf.reply', [
            'id' => 'limx',
        ], function (\Nats\Message $payload) {
            var_dump($payload->getBody());
        });

        return $this->response->success($res);
    }
}
