<?php

declare(strict_types=1);

namespace App\Command;

use App\Amqp\Producer\DemoProducer;
use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Psr\Container\ContainerInterface;

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
    }

    public function handle()
    {
        amqp_produce(new DemoProducer('command+' . uniqid()));
    }
}
