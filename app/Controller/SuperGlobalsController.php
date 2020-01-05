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

namespace App\Controller;

use Hyperf\HttpServer\Annotation\AutoController;

/**
 * @AutoController(prefix="sg")
 */
class SuperGlobalsController extends Controller
{
    public function index()
    {
        return $this->response->success([
            'get' => $_GET,
            'post' => $_POST,
            'file' => $_FILES,
            'cookie' => $_COOKIE,
            'request' => $_REQUEST,
            'server' => $_SERVER,
            'session' => $_SESSION,
        ]);
    }
}
