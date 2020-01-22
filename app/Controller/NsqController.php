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
        $nsq->publish('sample_topic', 'test');
        return $this->response->success();
    }
}
