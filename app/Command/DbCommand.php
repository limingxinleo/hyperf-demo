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
use Hyperf\Utils\Coroutine;
use Psr\Container\ContainerInterface;

/**
 * @Command
 */
class DbCommand extends HyperfCommand
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    protected $coroutine = true;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        parent::__construct('db:more');
    }

    public function configure()
    {
        $this->setDescription('循环处理 协程');
    }

    public function handle()
    {
        for ($i = 0; $i < 100000; ++$i) {
            $callback = function () {
                return Coroutine::id();
            };

            $arr[] = $callback;

            if ($i % 1000 === 0) {
                parallel($arr);
                echo $i . PHP_EOL;
                $arr = [];
            }
        }
    }
}
