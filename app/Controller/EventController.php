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

use App\Event\UserRegisted;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * @AutoController(prefix="event")
 */
class EventController extends Controller
{
    /**
     * @Inject
     * @var EventDispatcherInterface
     */
    protected $event;

    public function index()
    {
        $this->event->dispatch(new UserRegisted(1));

        return $this->response->success();
    }
}
