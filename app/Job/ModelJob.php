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

namespace App\Job;

use App\Model\Model;
use Hyperf\AsyncQueue\Job;

class ModelJob extends Job
{
    public $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function handle()
    {
        var_dump($this->model);
    }
}
