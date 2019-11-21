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
use Hyperf\Process\ProcessCollector;
use Swoole\Process;

/**
 * @AutoController
 */
class TcpController extends Controller
{
    public function send()
    {
        $processes = ProcessCollector::all();
        $data = serialize($this->request->input('data', 'asd')) . "\r\n";

        /** @var Process $process */
        foreach ($processes as $process) {
            $process->exportSocket()->send($data);
            $process->exportSocket()->send($data);
        }

        return 'success';
    }
}
