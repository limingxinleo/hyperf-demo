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

use App\Client\GrpcClient;
use Hyperf\Command\Annotation\Command;
use Hyperf\Command\Command as HyperfCommand;
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

        parent::__construct('grpc:test');
    }

    public function configure()
    {
        $this->setDescription('Hyperf Demo Command');
    }

    public function handle()
    {
        for ($i = 0; $i < 100; ++$i) {
            go(function () use ($i) {
                $client = new GrpcClient('127.0.0.1:9502', [
                    'credentials' => null,
                ]);

                while (true) {
                    $request = new \Grpc\HiUser();
                    $request->setName(uniqid());
                    $request->setSex(1);

                    [$reply, $status] = $client->sayHello($request);
                    var_dump($reply->getMessage());
                }
            });
        }
    }
}
