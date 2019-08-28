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

use Hyperf\Elasticsearch\ClientBuilderFactory;

class IndexController extends Controller
{
    public function index()
    {
        $client = di()->get(ClientBuilderFactory::class)->create()->setHosts(['http://127.0.0.1:9500'])->build();
        $data = $client->info();

        return $this->response->success($data);
    }
}
