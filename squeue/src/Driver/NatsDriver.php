<?php


namespace Hyperf\Squeue\Driver;


use Closure;
use Hyperf\Squeue\PublishInterface;
use Hyperf\Squeue\RequestInterface;
use Hyperf\Squeue\SubscribeInterface;

class NatsDriver implements PublishInterface, RequestInterface, SubscribeInterface
{
    public function __construct()
    {

    }

    public function publish(string $subject, string $payload = null, string $inbox = null)
    {
        // TODO: Implement publish() method.
    }

    public function request(string $subject, string $payload, Closure $callback)
    {
        // TODO: Implement request() method.
    }

    public function subscribe(string $subject, Closure $callback): string
    {
        // TODO: Implement subscribe() method.
    }
}