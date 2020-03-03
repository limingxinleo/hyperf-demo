<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\DemoService;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;

/**
 * @AutoController(prefix="async")
 */
class AsyncController extends Controller
{
    public function dump()
    {
        di()->get(DemoService::class)->dump(1, 'hyperf', ['data' => ['sex' => 1]]);
        di()->get(DemoService::class)->dump2(1, 'hyperf', ['data' => ['sex' => 1]]);
        di()->get(DemoService::class)->dump3(1, 'hyperf', ['data' => ['sex' => 1]]);

        return $this->response->success();
    }
}
