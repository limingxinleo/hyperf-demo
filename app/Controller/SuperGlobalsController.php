<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;

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
