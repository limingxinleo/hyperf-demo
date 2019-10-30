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

namespace Hyperf\Nats\Driver;

use Closure;
use Hyperf\Pool\SimplePool\PoolFactory;
use Nats\ConnectionOptions;
use Nats\EncodedConnection;
use Nats\Encoders\JSONEncoder;
use Psr\Container\ContainerInterface;

class NatsDriver extends AbstractDriver
{
    /**
     * @var \Hyperf\Pool\SimplePool\Pool
     */
    protected $pool;

    public function __construct(ContainerInterface $container, string $name, array $config)
    {
        parent::__construct($container, $name, $config);

        $factory = $this->container->get(PoolFactory::class);
        $poolConfig = $config['pool'] ?? [];

        $this->pool = $factory->get('squeue' . $this->name, function () use ($config) {
            $option = new ConnectionOptions($config['options'] ?? []);
            $encoder = make($config['encoder'] ?? JSONEncoder::class);
            return make(EncodedConnection::class, [$option, $encoder]);
        }, $poolConfig);
    }

    public function publish(string $subject, string $payload = null, string $inbox = null)
    {
        try {
            /** @var EncodedConnection $connection */
            $connection = $this->pool->get();
            $connection->publish($subject, $payload, $inbox);
        } finally {
            $connection->release();
        }
    }

    public function request(string $subject, string $payload, Closure $callback)
    {
        // TODO: Implement request() method.
    }

    public function subscribe(string $subject, Closure $callback): string
    {
        try {
            /** @var EncodedConnection $connection */
            $connection = $this->pool->get();
            $result = $connection->subscribe($subject, $callback);
        } finally {
            $connection->release();
        }

        return $result;
    }
}
