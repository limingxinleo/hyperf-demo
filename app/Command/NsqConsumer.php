<?php

declare(strict_types=1);

namespace App\Command;

use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Hyperf\Nsq\Message;
use Hyperf\Nsq\Nsq;
use Psr\Container\ContainerInterface;

/**
 * @Command
 */
class NsqConsumer extends HyperfCommand
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        parent::__construct('demo:command');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('Hyperf Demo Command');
    }

    public function handle()
    {
        $nsq = make(Nsq::class);
        $nsq->subscribe('sample_topic', 'test', function (Message $message) {
            var_dump($message->getMessageId(), $message->getBody());
        });
    }
}
