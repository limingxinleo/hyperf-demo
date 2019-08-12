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

use Hyperf\HttpServer\Annotation\AutoController;

/**
 * @AutoController
 */
class DbController extends Controller
{
    public function index()
    {
        $config = [
            'host' => 'localhost',
            'port' => '8123',
            'username' => 'default',
            'password' => '',
        ];
        $db = new \ClickHouseDB\Client($config);
        $db->database('default');
        $db->setTimeout(1.5);      // 1500 ms
        $db->setTimeout(10);       // 10 seconds
        $db->setConnectTimeOut(5); // 5 seconds

        go(function () use ($db) {
            var_dump($db->showTables());
        });

        var_dump(2);

        return $this->response->success();
    }
}
