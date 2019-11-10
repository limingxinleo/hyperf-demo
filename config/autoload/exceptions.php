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

use App\Exception\Handler\FooExceptionHandler;
use App\Exception\Handler\TestExceptionHandler;

return [
    'handler' => [
        'http' => [
            FooExceptionHandler::class,
            TestExceptionHandler::class,
            App\Exception\Handler\BusinessExceptionHandler::class,
        ],
    ],
];
