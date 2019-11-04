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

namespace App\Listener;

use App\Event\UserRegisted;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Logger\LoggerFactory;

/**
 * @Listener
 */
class UserRegistedListener implements ListenerInterface
{
    protected $logger;

    public function __construct(LoggerFactory $factory)
    {
        $this->logger = $factory->get('event');
    }

    public function listen(): array
    {
        return [
            UserRegisted::class,
        ];
    }

    public function process(object $event)
    {
        var_dump($event);
        $this->logger->info(get_class($event));
    }
}
