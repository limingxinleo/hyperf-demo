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

use Hyperf\Crontab\Annotation\Crontab;

/**
 * @Crontab(name="RefreshRepoStarCount", rule="* * * * * *", callback="execute", singleton=true)
 */
class DemoCrontab
{
    public function execute()
    {
        $a = '123';
        var_dump('before crontab.');
        var_dump(date('Y-m-d H:i:s', (int) $a));
        var_dump('after crontab.');
    }
}
