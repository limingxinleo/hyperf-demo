<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\AspectService;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;

/**
 * @AutoController()
 */
class AopController extends Controller
{
    public function index()
    {
        di()->get(AspectService::class)->handle();
        di()->get(AspectService::class)->handle2();
        di()->get(AspectService::class)->test();
        di()->get(AspectService::class)->test2();
        return 'success';
    }
}
