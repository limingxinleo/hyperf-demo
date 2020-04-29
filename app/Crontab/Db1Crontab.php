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
namespace App\Crontab;

use App\Model\User;
use Hyperf\Crontab\Annotation\Crontab;

/**
 * @Crontab(name="Db1Crontab", rule="*\/2 * * * * *", callback="execute")
 */
class Db1Crontab
{
    public function execute()
    {
        /** @var User $model */
        $model = User::query()->find(1);
        var_dump($model->id);
    }
}
