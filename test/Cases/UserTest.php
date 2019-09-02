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

use App\Model\Order;
use HyperfTest\HttpTestCase;

/**
 * @internal
 * @coversNothing
 */
class UserTest extends HttpTestCase
{
    public function testDbSelect()
    {
        $model = Order::find(37907888619521);
        if (empty($model)) {
            $model = new Order();
            $model->id = 37907888619521;
            $model->user_id = 8755870;
            $model->total_fee = rand(1, 999);
            $model->status = Order::STATUS_INIT;
            $model->sku_id = rand(1, 999);

            $model->save();
        }

        $this->assertSame('db2', $model->getRealConnectionName());

        $model = Order::find(37907888488450);
        if (empty($model)) {
            $model = new Order();
            $model->id = 37907888488450;
            $model->user_id = 9733949;
            $model->total_fee = rand(1, 999);
            $model->status = Order::STATUS_INIT;
            $model->sku_id = rand(1, 999);

            $model->save();
        }

        $this->assertSame('db1', $model->getRealConnectionName());
    }

    public function testCreateOrder()
    {
        $model = new Order();
        $model->user_id = rand(1000000, 9999999);
        $model->total_fee = rand(1, 999);
        $model->status = Order::STATUS_INIT;
        $model->sku_id = rand(1, 999);

        $this->assertTrue($model->save());
    }

    // public function testHasMany()
    // {
    //     $model = Order::find(37907888619521);
    //
    //     var_dump($model->logs);
    // }
}
