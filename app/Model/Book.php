<?php

declare (strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */
namespace App\Model;

/**
 * @property bigint|integer $id
 * @property bigint|integer $user_id
 * @property varchar|string $title
 * @property datetime|\Carbon\Carbon $created_at
 * @property datetime|\Carbon\Carbon $updated_at
 */
class Book extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'book';
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
    protected $fillable = ['id', 'user_id', 'title', 'created_at', 'updated_at'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'user_id' => 'integer'];
}