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

namespace App\Service;

use App\Model\User;
use Hyperf\Cache\Annotation\Cacheable;

class UserService
{
    /**
     * @Cacheable(prefix="cache:user", value="#{userId}")
     */
    public function find(int $userId, int $max = 100, array $ext = [])
    {
        var_dump($max, $ext);

        return User::query()->find($userId)->toArray();
    }
}
