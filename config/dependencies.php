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

use Hyperf\Database\Commands\Ast\ModelUpdateVisitor as Visitor;

return [
    'dependencies' => [
        Hyperf\Contract\StdoutLoggerInterface::class => App\Kernel\Log\LoggerFactory::class,
        Visitor::class => App\Kernel\Model\ModelUpdateVisitor::class,
    ],
];
