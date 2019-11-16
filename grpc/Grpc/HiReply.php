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

# source: grpc.proto

namespace Grpc;

use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>grpc.HiReply</code>.
 */
class HiReply extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>string message = 1;</code>.
     */
    private $message = '';

    /**
     * Generated from protobuf field <code>.grpc.HiUser user = 2;</code>.
     */
    private $user;

    /**
     * Constructor.
     *
     * @param array $data {
     *                    Optional. Data for populating the Message object.
     *
     *     @var string $message
     *     @var \Grpc\HiUser $user
     * }
     */
    public function __construct($data = null)
    {
        \GPBMetadata\Grpc::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>string message = 1;</code>.
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Generated from protobuf field <code>string message = 1;</code>.
     * @param string $var
     * @return $this
     */
    public function setMessage($var)
    {
        GPBUtil::checkString($var, true);
        $this->message = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>.grpc.HiUser user = 2;</code>.
     * @return \Grpc\HiUser
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Generated from protobuf field <code>.grpc.HiUser user = 2;</code>.
     * @param \Grpc\HiUser $var
     * @return $this
     */
    public function setUser($var)
    {
        GPBUtil::checkMessage($var, \Grpc\HiUser::class);
        $this->user = $var;

        return $this;
    }
}
