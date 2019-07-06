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

namespace App\JsonRpc\Server;

use App\JsonRpc\CalculatorServiceInterface;
use Hyperf\RpcServer\Annotation\RpcService;

/**
 * @RpcService(name="CalculatorTcpService", protocol="jsonrpc", server="jsonrpc", publishTo="consul")
 */
class CalculatorTcpService implements CalculatorServiceInterface
{
    // 实现一个加法方法，这里简单的认为参数都是 int 类型
    public function add(int $a, int $b): int
    {
        // 这里是服务方法的具体实现
        return $a + $b;
    }
}
