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

namespace App\Pool;

use Hyperf\Contract\ConnectionInterface;

class DbPool extends \Hyperf\DbConnection\Pool\DbPool
{
    public function get(): ConnectionInterface
    {
        var_dump('connections' . $this->currentConnections);
        return parent::get();
    }
}
