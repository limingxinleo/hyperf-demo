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
        $server = di()->get(Server::class)->getServer();

        $list = $server->getClientList();
        foreach ($list as $fd) {
            $info = $server->connection_info($fd);
            if ($info['websocket_status'] == WEBSOCKET_STATUS_ACTIVE) {
                $server->push($fd, 'Hello Hyperf.');
            }
        }

        return $this->response->success();
    }
}
