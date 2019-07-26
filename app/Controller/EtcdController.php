<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\Etcd\KVInterface;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;

/**
 * @AutoController()
 */
class EtcdController extends Controller
{
    public function get()
    {
        $client = di()->get(KVInterface::class);

        $result = $client->get("\0", ['range_end' => "\0"]);

        return $this->response->success($result);
    }
}
