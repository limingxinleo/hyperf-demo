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

use App\JsonRpc\CalculatorServiceConsumer;
use Hyperf\HttpServer\Annotation\AutoController;

/**
 * @AutoController
 */
class RpcController extends Controller
{
    public function send()
    {
        $client = di()->get(CalculatorServiceConsumer::class);

        return $client->add(1, 2);
    }
}
