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
namespace App\Service;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Model\User;
use Hyperf\DbConnection\Annotation\Transactional;

class DbService
{
    /**
     * @Transactional
     * @param mixed $success
     */
    public function execute($success = false)
    {
        /** @var User $model */
        $model = User::query()->where('id', 3)->first();

        $model->gender = rand(10, 99);

        $model->save();

        if (! $success) {
            throw new BusinessException(ErrorCode::SERVER_ERROR);
        }

        return true;
    }
}
