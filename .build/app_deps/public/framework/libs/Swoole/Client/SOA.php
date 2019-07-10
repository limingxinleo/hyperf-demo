<?php

namespace Swoole\Client;


use Swoole\Coroutine\RPC as CoRPC;

if (\Swoole\Core::$enableCoroutine)
{
    class SOA extends CoRPC
    {

    }
}
else
{
    class SOA extends RPC
    {

    }
}
