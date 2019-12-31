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

use App\JsonRpc\CalculatorServiceConsumer;
use App\JsonRpc\CalculatorServiceInterface;
use App\JsonRpc\MathValue;
use Hyperf\HttpServer\Annotation\AutoController;

/**
 * @AutoController
 */
class RpcController extends Controller
{
    public function send()
    {
        $client = di()->get(CalculatorServiceInterface::class);

        return $client->add(1, 2);
    }

    public function sum()
    {
        $client = di()->get(CalculatorServiceInterface::class);

        /** @var MathValue $result */
        $result = $client->sum(new MathValue(1), new MathValue(2));

        return $result->value;
    }

    public function arr()
    {
        $client = di()->get(CalculatorServiceInterface::class);

        return $client->arr();
    }

    public function objs()
    {
        $client = di()->get(CalculatorServiceInterface::class);

        return $client->objs();
    }

    public function exception()
    {
        $client = di()->get(CalculatorServiceInterface::class);

        return $client->exception();
    }
}
