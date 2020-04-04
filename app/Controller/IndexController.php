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

namespace App\Controller;

use App\Constants\ErrorCode;

class IndexController extends Controller
{
    public function index()
    {
        $user = $this->request->input('user', 'Hyperf');
        $method = $this->request->getMethod();
        return $this->response->success([
            'user' => $user,
            'method' => $method,
            'message' => ErrorCode::getMessage(ErrorCode::TOKEN_INVALID, [
                'token' => 'xxx',
            ]),
            'message3' => ErrorCode::getMessage(ErrorCode::TOKEN2_INVALID, ['xxx']),
            'message2' => ErrorCode::getMessage(ErrorCode::TOKEN2_INVALID, 'yyy'),
        ]);
    }
}
