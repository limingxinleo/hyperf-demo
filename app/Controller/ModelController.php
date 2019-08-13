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

use App\Model\Order;
use Hyperf\HttpServer\Annotation\AutoController;

/**
 * @AutoController
 */
class ModelController extends Controller
{
    public function create()
    {
        $userId = rand(1000000, 9999999);

        $order = new Order();
        $order->user_id = $userId;
        $order->total_fee = rand(1, 999);
        $order->status = Order::STATUS_INIT;
        $order->sku_id = rand(1, 999);

        $result = $order->save();

        return $this->response->success($result);
    }
}
