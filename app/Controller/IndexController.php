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

use Elasticsearch\ClientBuilder;
use Hyperf\Elasticsearch\ClientBuilderFactory;
use Hyperf\Guzzle\RingPHP\PoolHandler;

class IndexController extends Controller
{
    public function index()
    {
        $client = di()->get(ClientBuilderFactory::class)->create()->setHosts(['http://127.0.0.1:9200'])->build();

        $data = $client->info();

        return $this->response->success($data);
    }

    public function index2()
    {
        $builder = ClientBuilder::create();
        $builder->setHandler(make(PoolHandler::class));
        $client = $builder->setHosts(['http://127.0.0.1:9200'])->build();

        $data = $client->info();

        return $this->response->success($data);
    }
}
