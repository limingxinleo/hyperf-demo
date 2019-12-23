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

use Hyperf\JsonRpc\JsonRpcPoolTransporter;
use Hyperf\JsonRpc\JsonRpcTransporter;

return [
    Hyperf\Contract\StdoutLoggerInterface::class => App\Kernel\Log\LoggerFactory::class,
    // JsonRpcTransporter::class => function () {
    //     return make(JsonRpcPoolTransporter::class, [
    //         'config' => [
    //             'open_length_check' => true,
    //             'package_length_type' => 'N',
    //             'package_length_offset' => 0,
    //             'package_body_offset' => 4,
    //         ],
    //     ]);
    // },
];
