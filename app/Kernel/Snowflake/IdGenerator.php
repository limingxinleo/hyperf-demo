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

namespace App\Kernel\Snowflake;

use Hyperf\Di\Annotation\Inject;
use Hyperf\Snowflake\IdGeneratorInterface;

class IdGenerator
{
    /**
     * @Inject
     * @var MetaGenerator
     */
    protected $metaGenerator;

    /**
     * @Inject
     * @var IdGeneratorInterface
     */
    protected $idGenerator;

    /**
     * 根据 UserId 生成分布式主键 ID.
     * @param int $userId
     * @return int
     */
    public function generate(int $userId): int
    {
        $meta = $this->metaGenerator->generate($userId);

        return $this->idGenerator->generate($meta);
    }

    /**
     * 根据 ID 返回数据库ID.
     * @param int $id
     * @return int
     */
    public function degenerate(int $id): int
    {
        $meta = $this->idGenerator->degenerate($id);

        return $meta->getDataCenterId();
    }
}
