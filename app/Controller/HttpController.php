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

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Hyperf\Guzzle\HandlerStackFactory;
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
            ],
        ]);

        $client = new Client([
            'handler' => HandlerStack::create($handler),
            'base_uri' => 'http://127.0.0.1:8888',
        ]);

        return $client->get('/')->getBody()->getContents();
    }

    public function host()
    {
        $handler = make(PoolHandler::class, [
            'option' => [
                'max_connections' => 10,
            ],
        ]);

        $client = new Client([
            'handler' => HandlerStack::create($handler),
            'base_uri' => 'http://127.0.0.1:9501',
            'headers' => [
                'Host' => 'test',
                'X-ID' => uniqid(),
            ],
        ]);

        $options = [];
        // $options = ['headers' => ['Host' => 'test']];
        return $client->get('/', $options)->getBody()->getContents();
    }

    public function port()
    {
        $uri = $this->request->getUri();

        return $this->response->success($uri->getPort());
    }

    public function file()
    {
        var_dump($this->request->file('file'));
        var_dump($this->request->all());
        return $this->response->success();
    }

    public function upload()
    {
        $multipart = [];
        $file = BASE_PATH . '/.env.example';

        $multipart[] = [
            'name' => 'file',
            'contents' => fopen($file, 'r'),
            'filename' => basename($file),
        ];
        $client = new Client([
            'handler' => di()->get(HandlerStackFactory::class)->create(),
            'base_uri' => 'http://127.0.0.1:9501',
        ]);
        $response = $client->post('/http/file', [
            'query' => [
                'id' => 1,
                'name' => uniqid(),
            ],
            'multipart' => $multipart,
        ]);

        return $response->getBody()->getContents();
    }
}
