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

use App\Service\AopService;
use App\Task\ClassTask;
use App\Task\DemoTask;
use App\Task\MethodTask;
use App\Task\MongoTask;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\Task\Task;
use Hyperf\Task\TaskExecutor;
use Hyperf\Utils\Coroutine;

/**
 * @AutoController
 */
class TaskController extends Controller
{
    public function index()
    {
        $result = di()->get(TaskExecutor::class)->execute(new Task([DemoTask::class, 'handle'], [Coroutine::id()]));

        return $this->response->success($result);
    }

    public function method()
    {
        $cid = Coroutine::id();
        $result = di()->get(MethodTask::class)->handle($cid);
        $result2 = di()->get(MethodTask::class)->handle2($cid);

        return $this->response->success([
            'handle' => $result,
            'handle2' => $result2,
        ]);
    }

    public function class()
    {
        $cid = Coroutine::id();

        $result = di()->get(ClassTask::class)->handle($cid);

        return $this->response->success($result);
    }

    public function mongo()
    {
        $client = di()->get(MongoTask::class);
        $client->insert('hyperf.test', ['id' => rand(0, 99999999)]);

        $result = $client->query('hyperf.test', [], [
            'sort' => ['id' => -1],
            'limit' => 5,
        ]);

        return $this->response->success($result);
    }

    public function timeout()
    {
        $result = di()->get(MethodTask::class)->timeout();

        return $this->response->success($result);
    }

    public function exception()
    {
        $result = di()->get(MethodTask::class)->exception();

        return $this->response->success($result);
    }

    public function dump()
    {
        di()->get(AopService::class)->dump(1, 2, 3);
        di()->get(MethodTask::class)->dump(1, 2, 3);

        return $this->response->success();
    }
}
