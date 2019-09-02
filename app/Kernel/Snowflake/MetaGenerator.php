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
use Hyperf\Snowflake\Meta;
use Hyperf\Snowflake\MetaGeneratorInterface;

class MetaGenerator
{
    /**
     * @Inject
     * @var MetaGeneratorInterface
     */
    protected $metaGenerator;

    public function generate(int $userId): Meta
    {
        $meta = $this->metaGenerator->generate();

        // 充值 DataCenterId 为对应的 UserId
        return $meta->setDataCenterId($userId % 32);
    }
}
