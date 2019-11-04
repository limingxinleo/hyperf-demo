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

use Hyperf\HttpMessage\Uri\Uri;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\WebSocketClient\Client;

/**
 * @AutoController(prefix="ws-client")
 */
class WebSocketClientController extends Controller
{
    public function send()
    {
        $uri = new Uri('ws://127.0.0.1:9502');

        $client = new Client($uri);
        defer(function () use ($client) {
            $client->close();
        });

        $res = $client->recv();

        $client->push('adsfasdfasdf');

        return $client->recv();
    }
}
