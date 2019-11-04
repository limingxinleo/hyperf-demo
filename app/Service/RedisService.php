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

namespace App\Service;

use Hyperf\Utils\Str;

class RedisService
{
    public function __call($name, $arguments)
    {
        $method = 'call' . Str::studly($name);
        return $this->{$method}(...$arguments);
    }

    public function callTest(int &$id)
    {
        ++$id;
    }
}
