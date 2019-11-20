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

namespace App\JsonRpc;

use Hyperf\RpcServer\Annotation\RpcService;

/**
 * 注意，如希望通过服务中心来管理服务，需在注解内增加 publishTo 属性.
 * @RpcService(name=CalculatorServiceInterface::class, protocol="jsonrpc", server="jsonrpc-http")
 */
class CalculatorService implements CalculatorServiceInterface
{
    // @RpcService(name=CalculatorServiceInterface::class, protocol="jsonrpc-http", server="jsonrpc-http", publishTo="consul")

    // 实现一个加法方法，这里简单的认为参数都是 int 类型
    public function add(int $a, int $b): int
    {
        // 这里是服务方法的具体实现
        return $a + $b;
    }

    public function sum(MathValue $v1, MathValue $v2): MathValue
    {
        return new MathValue($v1->value + $v2->value);
    }

    public function arr(): array
    {
        return [1, 2, 3];
    }

    public function objs()
    {
        return [new MathValue(1), new MathValue(2)];
    }
}
