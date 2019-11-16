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

use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Crontab\Annotation\Crontab;
use Hyperf\Di\Annotation\Inject;

/**
 * @Crontab(name="RefreshRepoStarCount", rule="* * * * * *", callback="execute", singleton=true)
 */
class DemoCrontab
{
    /**
     * @Inject()
     * @var StdoutLoggerInterface
     */
    protected $logger;

    public function execute()
    {
        $a = '123';
        var_dump('before crontab.');
        $this->logger->info(date('Y-m-d H:i:s', (int)$a));
        var_dump('after crontab.');
    }
}
