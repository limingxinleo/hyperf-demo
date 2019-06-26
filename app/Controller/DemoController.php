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
}
