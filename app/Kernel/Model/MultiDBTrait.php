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

namespace App\Kernel\Model;

trait MultiDBTrait
{
    /**
     * Boot the soft deleting trait for a model.
     */
    public static function bootMultiDB()
    {
        static::addGlobalScope(new MultiDBScope());
    }
}
