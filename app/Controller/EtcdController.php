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
    public function index()
    {
        $client = di()->get(KVInterface::class);

        $key = $this->request->input('key', '/');

        $result = $client->fetchByPrefix($key);

        return $this->response->success($result);
    }
}
