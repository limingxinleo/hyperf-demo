<?php

declare(strict_types=1);

namespace App\Listener;

use Hyperf\Event\Annotation\Listener;
use Psr\Container\ContainerInterface;
use Hyperf\Event\Contract\ListenerInterface;
use Symfony\Component\Console\Event\ConsoleCommandEvent;

/**
 * @Listener
 */
class ConsoleCommandEventListener implements ListenerInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function listen(): array
    {
        return [
            ConsoleCommandEvent::class
        ];
    }

    /**
     * @param ConsoleCommandEvent $event
     */
    public function process(object $event)
    {
        var_dump($event->getInput());
    }
}
