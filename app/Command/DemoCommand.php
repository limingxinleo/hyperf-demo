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

namespace App\Command;

use App\Amqp\Producer\LargeProducer;
use Hyperf\Command\Annotation\Command;
use Hyperf\Command\Command as HyperfCommand;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Swoole\Timer;

/**
 * @Command
 */
class DemoCommand extends HyperfCommand
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    protected $coroutine = true;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        parent::__construct('demo:command');
    }

    public function configure()
    {
        $this->setDescription('Hyperf Demo Command');
        $this->eventDispatcher = $this->container->get(EventDispatcherInterface::class);
    }

    public function handle()
    {
        $data = [
            'id' => uniqid(),
            'data' => str_repeat(uniqid(), 1000),
        ];
        amqp_produce(new LargeProducer($data));
        amqp_produce(new LargeProducer($data));
        amqp_produce(new LargeProducer($data));
        amqp_produce(new LargeProducer($data));
        amqp_produce(new LargeProducer($data));

        // Timer::clearAll();
    }
}
