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

namespace App\Kernel\Model;

use Hyperf\Redis\Redis;
use Hyperf\Utils\Str;

class IdGenerator
{
    const REDIS_KEY = 'unique:id';

    /**
     * @var \Redis
     */
    protected $redis;

    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    /**
     * 根据 userId 生成 唯一主键.
     * @param int $userId
     * @return int
     */
    public function generate(int $userId): int
    {
        $incr = (string) $this->redis->incr(self::REDIS_KEY);
        $suffix = str_pad($incr, 3, '0', STR_PAD_LEFT);

        $userId = (string) ($userId % 100);
        $middle = str_pad($userId, 2, '0', STR_PAD_LEFT);

        return (int) sprintf('%s%s%s', time(), $middle, $suffix);
    }

    /**
     * 根据唯一主键，反推 userId 末两位.
     * @return int
     */
    public function degenerate(int $id): int
    {
        return (int) Str::substr((string) $id, -5, 2);
    }
}
