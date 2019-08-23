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

namespace App\Listener;

use App\Model\User;
use Hyperf\Database\Model\Events\Saving;
use Hyperf\ModelListener\AbstractListener;
use Hyperf\ModelListener\Observer;

/**
 * @Observer(User::class)
 */
class UserObserver extends AbstractListener
{
    public function saving(Saving $event)
    {
        var_dump(get_class($event->getModel()) . ' observer method saving');
    }
}
