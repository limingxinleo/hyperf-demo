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
use Psr\Container\ContainerInterface;
use Swoole\Coroutine\Socket;

/**
 * @Command
 */
class TcpCommand extends HyperfCommand
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        parent::__construct('tcp:send');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('Send TCP Data.');
    }

    public function handle()
    {
        $data = [
            ['h', 'e', 'l', 'l', 'o'],
            ['H', 'y', 'p', 'e', 'r', 'f'],
        ];

        foreach ($data as $datum) {
            $socket = new Socket(AF_INET, SOCK_STREAM, 0);
            $retval = $socket->connect('localhost', 9502);

            foreach ($datum as $item) {
                $n = $socket->send($item);
            }

            $socket->close();
        }
    }
}
