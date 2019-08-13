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

namespace HyperfTest\Cases;

use App\Kernel\Model\IdGenerator;
use App\Model\Order;
use HyperfTest\HttpTestCase;

/**
 * @internal
 * @coversNothing
 */
class UserTest extends HttpTestCase
{
    public function testUserCreate()
    {
        $userId = rand(1000000, 9999999);

        $order = new Order();
        $order->user_id = $userId;
        $order->total_fee = rand(1, 999);
        $order->status = Order::STATUS_INIT;
        $order->sku_id = rand(1, 999);

        $this->assertTrue($order->save());
    }

    public function testUserFind()
    {
        $order = Order::find(156569356749045);

        $this->assertNotEmpty($order);
    }

    public function testIdGeneratorDegenerate()
    {
        $userIds = [
            1234456 => 56,
            111111 => 11,
            1010101 => 1,
        ];

        foreach ($userIds as $userId => $did) {
            $generator = di()->get(IdGenerator::class);
            $id = $generator->generate($userId);
            $this->assertSame($did, $generator->degenerate($id));
        }
    }
}
