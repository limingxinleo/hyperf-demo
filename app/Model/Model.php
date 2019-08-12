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

use Hyperf\Database\ConnectionInterface;
use Hyperf\DbConnection\ConnectionResolver;
use Hyperf\DbConnection\Model\Model as BaseModel;
use Hyperf\ModelCache\Cacheable;
use Hyperf\ModelCache\CacheableInterface;

abstract class Model extends BaseModel implements CacheableInterface
{
    use Cacheable;

    public function getRealConnectionName($id = null)
    {
        if ($id) {
            $this->id = $id;
        }

        if ($this->id % 2) {
            return 'db2';
        }
        return 'db1';
    }

    public function save(array $options = []): bool
    {
        if (is_null($this->id)) {
            $this->id = $this->newId();
        }

        return parent::save($options);
    }

    public static function find($id)
    {
        return (new static())->setConnectionById($id)->newQuery()->find($id);
    }

    public function setConnectionById($id)
    {
        return parent::setConnection($this->getRealConnectionName($id));
    }

    /**
     * Get the database connection for the model.
     */
    public function getConnection(): ConnectionInterface
    {
        $connectionName = $this->getRealConnectionName();
        $resolver = $this->getContainer()->get(ConnectionResolver::class);
        return $resolver->connection($connectionName);
    }

    protected function newId()
    {
        $redis = $this->getContainer()->get(\Redis::class);
        $incr = (string) $redis->incr('unique:id');
        $incr = str_pad($incr, 5, '0', STR_PAD_LEFT);

        return (int) sprintf('%s%s', time(), $incr);
    }
}
