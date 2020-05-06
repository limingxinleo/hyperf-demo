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

use App\Service\GuzzleService;
use Hyperf\CircuitBreaker\Annotation\CircuitBreaker;
use Hyperf\Di\Annotation\Inject;

class IndexController extends Controller
{
    /**
     * @Inject()
     * @var GuzzleService
     */
    protected $service;

    public function index()
    {
        $user = $this->request->input('user', 'Hyperf');
        $method = $this->request->getMethod();
        return $this->response->success([
            'user' => $user,
            'method' => $method,
            'message' => 'Hello Hyperf.',
        ]);
    }

    /**
     * @CircuitBreaker(failCounter=1, fallback="App\Controller\IndexController::index", timeout=0.1)
     */
    public function breaker()
    {
        $this->service->client()->get('/sleep');

        return $this->response->success('breaking...');
    }

    public function sleep()
    {
        sleep(1);
        return $this->response->success('breaking...');
    }
}
