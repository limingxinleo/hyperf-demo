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
namespace App\Model;

use App\Caster\UserInfoCaster;
use Hyperf\Database\Model\Builder;

/**
 * @property int $id
 * @property string $name
 * @property int $gender
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \App\Model\Book $book
 * @property \App\Model\Book[]|\Hyperf\Database\Model\Collection $books
 * @property null|\App\Model\Book|int|string $test
 * @property \App\Caster\UserInfo $userInfo
 */
class User extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'name', 'gender', 'created_at', 'updated_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['userInfo' => UserInfoCaster::class, 'id' => 'integer', 'gender' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public function book()
    {
        return $this->hasOne(Book::class, 'user_id', 'id');
    }

    public function books()
    {
        return $this->hasMany(Book::class, 'user_id', 'id');
    }

    /**
     * @return null|Book|int|string
     */
    public function getTestAttribute()
    {
        return uniqid();
    }

    /**
     * @param Builder $query
     */
    public function scopeMan($query)
    {
        return $query->where('gender', 1);
    }
}
