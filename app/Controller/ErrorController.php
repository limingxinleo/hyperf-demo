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
use App\Exception\BusinessException;
use Hyperf\HttpServer\Annotation\AutoController;

/**
 * @AutoController(prefix="/error")
 */
class ErrorController extends Controller
{
    public function index()
    {
        // throw new BusinessException(ErrorCode::SERVER_ERROR);

        // return $this->response->success(ErrorCode::getMessage(ErrorCode::PARAMS_INVALID));

        return $this->response->success(ErrorCode::getMessage(ErrorCode::PARAMS_INVALID, ['param' => 'userId']));
    }
}
