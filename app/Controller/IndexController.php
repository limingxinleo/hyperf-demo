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

use Hyperf\Logger\LoggerFactory;

class IndexController extends Controller
{
    public function index()
    {
        $user = $this->request->input('user', 'Hyperf');
        $method = $this->request->getMethod();

        $data = uniqid();
        $logger = di()->get(LoggerFactory::class)->get('hyperf');
        $logger->info($data);

        $logger = di()->get(LoggerFactory::class)->get('hyperf', 'multi');
        $logger->info($data);
        $logger->debug('xxxx');

        return $this->response->success([
            'user' => $user,
            'method' => $method,
            'message' => 'Hello Hyperf.',
        ]);
    }
}
