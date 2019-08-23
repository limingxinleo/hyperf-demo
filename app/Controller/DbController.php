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

namespace App\Controller;

use App\Model\User;
use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Annotation\AutoController;

/**
 * @AutoController
 */
class DbController extends Controller
{
    public function roll()
    {
        $conn = Db::connection();
        $transactions = $conn->transactionLevel();

        Db::beginTransaction();
        Db::beginTransaction();

        return $this->response->success($transactions);
    }

    public function saving()
    {
        $user = User::find(1);

        $user->save();

        return $this->response->success();
    }
}
