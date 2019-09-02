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

namespace App\Model;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Kernel\Model\MultiDBTrait;
use App\Kernel\Snowflake\IdGenerator;
use Hyperf\Database\ConnectionInterface;
use Hyperf\DbConnection\ConnectionResolver;
use Hyperf\DbConnection\Model\Model as BaseModel;
use Hyperf\ModelCache\Cacheable;
use Hyperf\ModelCache\CacheableInterface;

abstract class Model extends BaseModel implements CacheableInterface
{
    use Cacheable;
    use MultiDBTrait;

    /**
     * 根据主键ID获取对应 ConnectionName.
     * @param null|int $id
     * @return string
     */
    public function getRealConnectionName(int $id = null)
    {
        if (is_null($id)) {
            if (empty($this->id)) {
                throw new BusinessException(ErrorCode::DB_SELECT_FAILED);
            }

            $id = $this->id;
        }

        $generator = $this->getContainer()->get(IdGenerator::class);

        $did = $generator->degenerate($id);

        if ($did % 2 == 0) {
            return 'db2';
        }
        return 'db1';
    }

    public function save(array $options = []): bool
    {
        if (is_null($this->id) && ! $this->incrementing) {
            if (empty($this->user_id)) {
                throw new BusinessException(ErrorCode::DB_SELECT_FAILED);
            }

            $this->id = di()->get(IdGenerator::class)->generate($this->user_id);
        }

        return parent::save($options);
    }

    public static function find(int $id)
    {
        return (new static())->loadConnnection($id)->newQuery()->find($id);
    }

    /**
     * Reset connection name by id.
     * @param $id
     * @return BaseModel
     */
    public function loadConnnection(int $id)
    {
        return parent::setConnection($this->getRealConnectionName($id));
    }

    /**
     * Get the database connection for the model.
     */
    public function getConnection(): ConnectionInterface
    {
        $connectionName = $this->getConnectionName();
        if ($connectionName == 'default') {
            // 如果没有选择正确的数据库，则重新选择
            $connectionName = $this->getRealConnectionName();
        }
        $resolver = $this->getContainer()->get(ConnectionResolver::class);

        return $resolver->connection($connectionName);
    }
}
