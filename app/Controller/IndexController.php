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

use App\Service\ElasticService;
use Hyperf\Di\Annotation\Inject;

class IndexController extends Controller
{
    /**
     * @Inject
     * @var ElasticService
     */
    protected $service;

    public function index()
    {
        $client = $this->service->client();

        $result = $client->info();

        var_dump(memory_get_usage());

        return $this->response->success($result);
    }
}
