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

namespace App\Controller;

use App\Server;
use Hyperf\HttpServer\Annotation\AutoController;

/**
 * @AutoController
 */
class HttpController extends Controller
{
    public function index()
    {
        $fd = $this->request->input('fd');

        $server = di()->get(Server::class)->getServer();

        $server->push((int) $fd, 'Hello Hyperf.');

        return $this->response->success();
    }
}
