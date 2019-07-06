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

namespace App\JsonRpc\Client;

use App\JsonRpc\CalculatorServiceInterface;
use Hyperf\RpcClient\AbstractServiceClient;

class CalculatorService extends AbstractServiceClient implements CalculatorServiceInterface
{
    /**
     * 定义对应服务提供者的服务名称.
     * @var string
     */
    protected $serviceName = 'CalculatorService';

    /**
     * 定义对应服务提供者的服务协议.
     * @var string
     */
    protected $protocol = 'jsonrpc';

    public function add(int $a, int $b): int
    {
        return $this->__request(__FUNCTION__, compact('a', 'b'));
    }
}
