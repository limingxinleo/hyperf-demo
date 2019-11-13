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

use Hyperf\Contract\SessionInterface;
use Hyperf\HttpServer\Annotation\AutoController;

/**
 * @AutoController
 */
class SessionController extends Controller
{
    public function index()
    {
        $session = di()->get(SessionInterface::class);
        $result = $session->get('test');
        return $this->response->success($result);
    }

    public function set()
    {
        $session = di()->get(SessionInterface::class);
        $result = $session->set('test', uniqid());
        return $this->response->success($result);
    }
}
