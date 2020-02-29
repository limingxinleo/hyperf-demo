<?php


namespace App\Service;


use Hyperf\Guzzle\HandlerStackFactory;
use Hyperf\Guzzle\RetryMiddleware;

class GuzzleService
{
    public function client()
    {

    }

    protected function handler()
    {
        di()->get(HandlerStackFactory::class)->create([], [
            'retry' => [RetryMiddleware::class, [1, 10]],
        ]);
    }
}