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
 * @AutoController(prefix="fre")
 */
class FrequencyController extends Controller
{
    public function db()
    {
        $user = User::query()->find(1);

        return $user->toArray();
    }

    public function redis()
    {
        return di()->get(\Redis::class)->keys('*');
    }
}
