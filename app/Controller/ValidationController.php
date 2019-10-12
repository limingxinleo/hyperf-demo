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

use App\Request\RequiredRequest;
use Hyperf\HttpServer\Annotation\AutoController;

/**
 * @AutoController(prefix="validation")
 */
class ValidationController extends Controller
{
    public function required(RequiredRequest $request)
    {
        return $request->all();
    }
}
