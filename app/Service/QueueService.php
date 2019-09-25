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

namespace App\Service;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Job\ExampleJob;
use App\Model\User;
use Hyperf\AsyncQueue\Annotation\AsyncQueueMessage;
use Hyperf\AsyncQueue\Driver\DriverFactory;
use Hyperf\AsyncQueue\Driver\DriverInterface;

class QueueService
{
    /**
     * @var DriverInterface
     */
    protected $driver;

    public function __construct(DriverFactory $driverFactory)
    {
        $this->driver = $driverFactory->get('default');
    }

    /**
     * 投递消息.
     * @param $params 数据
     * @param int $delay 延时时间 单位秒
     * @return bool
     */
    public function push($params, $delay = 0)
    {
        // 这里的 `ExampleJob` 会被序列化存到 Redis 中，所以内部变量最好只传入普通数据
        // 同理，如果内部使用了注解 @Value 会把对应对象一起序列化，导致消息体变大。
        return $this->driver->push(new ExampleJob($params), $delay);
    }

    /**
     * @AsyncQueueMessage
     * @param mixed $params
     */
    public function annotation($params)
    {
        var_dump($params);
    }

    /**
     * @AsyncQueueMessage(delay=1)
     * @param mixed $params
     */
    public function annotationdelay($params)
    {
        var_dump($params);
    }

    /**
     * @AsyncQueueMessage(delay=1)
     * @param mixed $params
     */
    public function annotationmodel(User $user)
    {
        var_dump(111);
        var_dump($user);
    }

    /**
     * @AsyncQueueMessage
     */
    public function retry()
    {
        throw new BusinessException(ErrorCode::SERVER_ERROR);
    }

    /**
     * @AsyncQueueMessage
     */
    public function oneMinute()
    {
        sleep(1);
        di()->get(\Redis::class)->incr('asdf');
    }
}
