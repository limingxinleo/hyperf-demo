<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\User;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;

/**
 * @AutoController(prefix="model")
 */
class ModelController extends Controller
{
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        $user = User::query()->first();
        $res = $user->joinedGroups;

        return $this->response->success($res->toArray());
    }
}
