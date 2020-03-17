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

use Hyperf\Command\Annotation\Command;
use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Contract\ConfigInterface;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * @Command
 */
class EtcdCommand extends HyperfCommand
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        parent::__construct('etcd:show');
    }

    public function configure()
    {
        parent::configure();
        $this->eventDispatcher = $this->container->get(EventDispatcherInterface::class);
        $this->setDescription('展示ETCD配置');
    }

    public function handle()
    {
        $config = di()->get(ConfigInterface::class);

        var_dump($config->get('etcd'));
    }
}
