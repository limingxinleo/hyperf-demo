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

use App\Service\LazyService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;

/**
 * @AutoController(prefix="lazy")
 */
class LazyController extends Controller
{
    /**
     * @Inject(lazy=true)
     * @var LazyService
     */
    protected $service;

    public function index()
    {
        return $this->response->success([
            'time' => time(),
            'id' => $this->service->id(),
        ]);
    }
}
