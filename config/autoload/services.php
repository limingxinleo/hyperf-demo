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

return [
    'consumers' => [
        [
            // 对应消费者类的 $serviceName
            'name' => \App\JsonRpc\CalculatorServiceInterface::class,
            // 'protocol' => 'jsonrpc',
            'protocol' => 'jsonrpc-tcp-length-check',
            // 这个消费者要从哪个服务中心获取节点信息，如不配置则不会从服务中心获取节点信息
            // 'registry' => [
            //     'protocol' => 'consul',
            //     'address' => 'http://127.0.0.1:8500',
            // ],
            // 如果没有指定上面的 registry 配置，即为直接对指定的节点进行消费，通过下面的 nodes 参数来配置服务提供者的节点信息
            'nodes' => [
                ['host' => '127.0.0.1', 'port' => 9504],
            ],
            'options' => [
                'connect_timeout' => 5.0,
                'settings' => [
                    // 'open_eof_split' => true,
                    // 'package_eof' => "\r\n",
                    'open_length_check' => true,
                    'package_length_type' => 'N',
                    'package_length_offset' => 0,
                    'package_body_offset' => 4,
                ],
                'pool' => [
                    'min_connections' => 1,
                    'max_connections' => 32,
                    'connect_timeout' => 10.0,
                    'wait_timeout' => 3.0,
                    'heartbeat' => -1,
                    'max_idle_time' => 60.0,
                ],
                'recv_timeout' => 5.0,
            ],
        ],
    ],
];
