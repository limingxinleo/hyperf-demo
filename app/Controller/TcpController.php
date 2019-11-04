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

use App\JsonRpc\Client\CalculatorService;
use Hyperf\HttpServer\Annotation\AutoController;

/**
 * @AutoController(prefix="/tcp")
 */
class TcpController extends Controller
{
    public function send()
    {
        $client = di()->get(CalculatorService::class);

        $res = $client->add(1, 2);

        return $this->response->success($res);
    }
}
