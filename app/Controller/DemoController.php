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

use App\Service\MakeService;
use GuzzleHttp\RequestOptions;
use Hyperf\Guzzle\ClientFactory;
use Hyperf\HttpServer\Annotation\AutoController;

/**
 * @AutoController(prefix="/demo")
 */
class DemoController extends Controller
{
    public function make()
    {
        $service = make(MakeService::class, ['id' => uniqid()]);

        return $this->response->success([
            'id' => $service->getId(),
        ]);
    }

    public function guzzle()
    {
        $client = di()->get(ClientFactory::class)->create([
            'base_uri' => 'https://api.github.com',
        ]);

        $response = $client->get('/', [
            RequestOptions::JSON => ['a' => 'a', 'b' => 'b'],
            RequestOptions::VERIFY => false,
            RequestOptions::HEADERS => [
                'appid' => '8ab74856-8772-45c9-96db-54cb30ab9f74',
                'timestamp' => '1561536304',
                'apisign' => '7f04cb6bcd787daba31caf7ef569e4f4',
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);

        return $response->getBody()->getContents();
    }

    public function file()
    {
        $has = $this->request->hasFile('file');

        $file = $this->request->file('file');

        return $this->response->success([
            'has' => $has,
            'file' => $file->toArray(),
            'path' => $file->getPath(),
        ]);
    }
}
