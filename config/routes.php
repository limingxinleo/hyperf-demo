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

use Hyperf\HttpServer\Router\Router;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController::index', [
    'middleware' => [
        \App\Middleware\UserMiddleware::class,
    ],
]);

Router::addRoute(['GET', 'POST', 'HEAD'], '/user/{id:\d+}', 'App\Controller\IndexController@user', [
    'middleware' => [
        \App\Middleware\UserMiddleware::class,
    ],
]);
