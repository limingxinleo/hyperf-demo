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

namespace App\Service\InjectTest;

use Hyperf\Di\Annotation\Inject;

class Demo
{
    /**
     * @Inject
     * @var A
     */
    public $a;

    /**
     * @var B
     */
    public $b;

    public function __construct(B $b)
    {
        $this->b = $b;
    }
}
