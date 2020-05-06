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
namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Hyperf\Guzzle\HandlerStackFactory;

class GuzzleService
{
    /**
     * @var HandlerStack
     */
    protected $handler;

    public function __construct(HandlerStackFactory $factory)
    {
        $this->handler = $factory->create();
    }

    public function client()
    {
        return new Client([
            'handler' => $this->handler,
            'base_uri' => 'http://127.0.0.1:9501',
        ]);
    }
}
