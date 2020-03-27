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

use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\Nsq\Nsq;

/**
 * @AutoController(prefix="nsq")
 */
class NsqController extends Controller
{
    public function index()
    {
        $nsq = make(Nsq::class);
        $message = 'test';
        $message = $message = str_pad('1386', 16*1024, 'a') . '1386';
        $nsq->publish('test', $message);
        return $this->response->success();
    }
}
