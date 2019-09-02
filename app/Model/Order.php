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

namespace App\Model;

use Hyperf\Database\Model\Events\Saved;

/**
 * @property int $id
 * @property int $user_id
 * @property int $total_fee
 * @property int $sku_id
 * @property int $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Order extends Model
{
    const STATUS_INIT = 0;

    const STATUS_PAID = 1;

    const STATUS_CANCEL = 2;

    public $incrementing = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order';

    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'default';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'user_id', 'total_fee', 'sku_id', 'status', 'created_at', 'updated_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'user_id' => 'integer', 'total_fee' => 'integer', 'sku_id' => 'integer', 'status' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public function saved(Saved $event)
    {
        $model = new OrderLog();
        $model->order_id = $this->id;
        $model->user_id = $this->user_id;
        $model->total_fee = $this->total_fee;
        $model->sku_id = $this->sku_id;
        $model->status = $this->status;

        $model->setConnection($this->getRealConnectionName());

        $model->save();
    }

    public function logs()
    {
        return $this->hasMany(OrderLog::class, 'order_id', 'id');
    }
}
