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

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use Grpc\HiReply;
use Grpc\HiUser;

class GrpcController extends Controller
{
    public function sayHello(HiUser $user)
    {
        if ($user->getSex() == 0) {
            throw new BusinessException(ErrorCode::SEX_INVALID);
        }

        $response = new HiReply();
        $response->setUser($user);
        $response->setMessage('Hello Hyperf!');

        return $response;
    }
}
