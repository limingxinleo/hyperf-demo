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
use Hyperf\Guzzle\ClientFactory;
use Hyperf\Utils\Parallel;
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
        $parallel = new Parallel();
        $parallel->add(function () {
            $client = di()->get(ClientFactory::class)->create();
            $res = $client->get('https://www.baidu.com')->getBody()->getContents();
            var_dump(strlen($res));
        });

        $parallel->add(function () {
            $client = di()->get(ClientFactory::class)->create();
            $res = $client->get('https://github.com')->getBody()->getContents();
            var_dump(strlen($res));
        });

        while (true) {
            $parallel->wait();
            sleep(1);
        }
    }
}
