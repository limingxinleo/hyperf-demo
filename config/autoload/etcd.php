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

return [
    'uri' => 'http://192.168.1.200:2379',
    'version' => 'v3beta',
    'options' => [
        'timeout' => 10,
    ],

    # Etcd Config Center
    'enable' => true,
    'namespaces' => [
        '/test',
    ],
    'mapping' => [
        '/test/test' => 'etcd.test.test',
    ],
    'interval' => 5,
];
