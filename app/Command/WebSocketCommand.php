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
use Hyperf\HttpMessage\Uri\Uri;
use Hyperf\WebSocketClient\Client;
use Psr\Container\ContainerInterface;

/**
 * @Command
 */
class WebSocketCommand extends HyperfCommand
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        parent::__construct('ws:recv');
    }

    public function configure()
    {
        $this->setDescription('Hyperf Demo Command');
    }

    public function handle()
    {
        $client = new Client(new Uri('http://127.0.0.1:9502/ws'));

        $client->push('xxxx');

        while (true) {
            var_dump($client->recv());
        }
    }
}
