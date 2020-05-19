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
 * @AutoController(prefix="cast")
 */
class CastController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = User::query()->find(100);
        $user->userInfo->name = 'John1';
        // $user->syncAttributes();
        // $user->syncAttributes();
        // $user->syncAttributes();
        // $user->syncAttributes();
        return $this->response->success($user->getAttributes());
    }
}
