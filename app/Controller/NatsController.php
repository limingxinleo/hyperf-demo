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
use Hyperf\Nats\Driver\DriverInterface;

/**
 * @AutoController(prefix="nats")
 */
class NatsController extends Controller
{
    /**
     * @Inject
     * @var DriverInterface
     */
    protected $nats;

    public function publish()
    {
        $res = $this->nats->publish('hyperf.demo', [
            'id' => 'limx',
        ]);

        return $this->response->success($res);
    }

    public function pool()
    {
        $factory = di()->get(DriverFactory::class);
        $factory->get('nats');
    }

    public function request()
    {
        $res = $this->nats->request('hyperf.reply', [
            'id' => 'limx',
        ], function (\Hyperf\Nats\Message $payload) {
            var_dump($payload->getBody());
        });

        return $this->response->success($res);
    }

    public function sync()
    {
        $res = $this->nats->requestSync('hyperf.reply', [
            'id' => 'limx',
        ]);

        return $this->response->success($res->getBody());
    }
}
