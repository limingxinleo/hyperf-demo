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

use Hyperf\AsyncQueue\Annotation\AsyncQueueMessage;

class DemoService
{
    /**
     * @AsyncQueueMessage
     */
    public function dump(...$params)
    {
        var_dump($params);
    }

    /**
     * @AsyncQueueMessage
     */
    public function dump3($id, ...$params)
    {
        var_dump($id, $params);
    }

    public function dump2(...$params)
    {
        var_dump($params);
    }
}
