<?php

return [
    'uri' => 'http://192.168.1.200:2379',
    'version' => 'v3beta',
    'options' => [
        'timeout' => 10,
    ],

    # Etcd Config Center
    'enable' => false,
    'namespaces' => [
        'application',
    ],
    'mapping' => [
    ],
    'interval' => 5,
];