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

use App\Model\User;
use Hyperf\HttpServer\Annotation\AutoController;

/**
 * @AutoController
 */
class ModelController extends Controller
{
    public function create()
    {
        $user = new User();
        $user->name = uniqid();
        $user->gender = rand(0, 1);
        $user->save();

        return $user->toArray();
    }

    public function get()
    {
        $id = $this->request->input('id');

        $user = User::find($id);

        return $user->toArray();
    }
}
