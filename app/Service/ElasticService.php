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

use Elasticsearch\ClientBuilder;
use Hyperf\Guzzle\RingPHP\PoolHandler;

class ElasticService
{
    /**
     * @var PoolHandler
     */
    protected $handler;

    public function __construct(PoolHandler $handler)
    {
        $this->handler = $handler;
    }

    public function client()
    {
        return ClientBuilder::create()
            ->setHosts(['http://127.0.0.1:9200', 'http://127.0.0.1:9200'])
            ->setHandler($this->handler)
            ->build();
    }
}
