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
