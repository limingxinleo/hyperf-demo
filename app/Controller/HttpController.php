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

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Hyperf\Guzzle\PoolHandler;
use Hyperf\HttpServer\Annotation\AutoController;

/**
 * @AutoController
 */
class HttpController extends Controller
{
    public function header()
    {
        $version = $this->request->header('Version', 'v1');

        return $this->response->success($version);
    }

    public function execute()
    {
        $handler = make(PoolHandler::class, [
            'option' => [
                'max_connections' => 10,
            ]
        ]);

        $client = new Client([
            'handler' => HandlerStack::create($handler),
            'base_uri' => 'http://127.0.0.1:8888'
        ]);

        return $client->get('/')->getBody()->getContents();
    }
}
