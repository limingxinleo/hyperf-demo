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

use App\Model\User;
use Hyperf\HttpServer\Annotation\AutoController;

/**
 * @AutoController(prefix="model")
 */
class ModelController extends Controller
{
    public function index()
    {
        $user = User::query()->find(1);

        return $this->response->success($user);
    }
}
