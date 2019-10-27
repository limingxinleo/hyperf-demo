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

use App\Job\ConfigCenterJob;
use Hyperf\Contract\ConfigInterface;
use Hyperf\HttpServer\Annotation\AutoController;

/**
 * @AutoController
 */
class EtcdController extends Controller
{
    public function index()
    {
        $config = di()->get(ConfigInterface::class);

        queue_push(new ConfigCenterJob());

        return $this->response->success($config->get('etcd'));
    }
}
