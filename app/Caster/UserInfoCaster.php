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
namespace App\Caster;

use Hyperf\Contract\CastsAttributes;

class UserInfoCaster implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        return new UserInfo($attributes['name'], $attributes['gender']);
    }

    /**
     * @param object $model
     * @param UserInfo $value
     * @return array|string
     */
    public function set($model, string $key, $value, array $attributes)
    {
        return [
            'name' => $value->name,
            'gender' => $value->gender,
        ];
    }
}
