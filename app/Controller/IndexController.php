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

use App\Model\UserExt;

class IndexController extends Controller
{
    public function index()
    {
        /** @var UserExt $model */
        $model = UserExt::query()->find(1);
        $model->float_num = $model->float_num + 0.1;
        return $this->response->success($model->save());
    }
}
