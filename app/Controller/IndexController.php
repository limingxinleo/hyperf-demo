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

class IndexController extends Controller
{
    public function index()
    {
        $user = User::query()->find(2);
        $result = $user->visitedGroups->toArray();
        return $user->visitedGroups()
            ->limit(7)
            ->latest('group_visited.created_at')
            ->get();
    }
}
