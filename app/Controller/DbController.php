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

use App\Annotation\Formatter;
use App\Model\User;
use App\Model\UserExt;
use App\Service\UserService;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\RequestMapping;

/**
 * @AutoController(prefix="db")
 */
class DbController
{
    public function index()
    {
        return di()->get(UserService::class)->find(1);
    }

    public function model()
    {
        /** @var User $user */
        $user = User::query()->find(1);

        return $user->created_at->toDateString();
    }

    public function json()
    {
        /** @var UserExt $user */
        $user = UserExt::query()->find([1, 2]);

        $ext = UserExt::findManyFromCache([1, 2]);

        return $user;
    }

    /**
     * @Formatter
     */
    public function format()
    {
        return [];
    }

    /**
     * @RequestMapping(path="fo")
     */
    public function fo()
    {
        return [];
    }
}
