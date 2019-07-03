<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace App\Service;

use Hyperf\Contract\StdoutLoggerInterface;
use Psr\Container\ContainerInterface;

class MakeService
{
    public $id;

    protected $container;

    public function __construct(ContainerInterface $container, string $id)
    {
        $this->container = $container;
        $this->id = $id;
    }

    public function getId()
    {
        $logger = $this->container->get(StdoutLoggerInterface::class);

        $logger->info('id=' . $this->id);

        return $this->id;
    }
}
