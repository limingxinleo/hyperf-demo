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
    public function add()
    {
        $user = new User();
        $user->name = uniqid();
        $user->gender = 1;
        return $this->response->success($user->save());
    }

    public function info()
    {
        $user = User::findFromCache($this->request->input('id', 0));

        return $this->response->success(
            $user ? $user->toArray() : null
        );
    }

    public function delete()
    {
        $res = User::query(true)->where('id', '>', 100)->delete();

        return $this->response->success($res);
    }
}
