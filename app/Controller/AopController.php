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

use App\Service\AspectService;
use Hyperf\HttpServer\Annotation\AutoController;

/**
 * @AutoController
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
