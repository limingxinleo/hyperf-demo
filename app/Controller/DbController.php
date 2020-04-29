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
namespace App\Controller;

use App\Model\User;
use App\Model\UserExt;
use App\Model\UserRole;
use App\Service\DbService;
use Hyperf\DbConnection\Collector\TableCollector;
use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Annotation\AutoController;

/**
 * @AutoController
 */
class DbController extends Controller
{
    public function first()
    {
        $data = User::query()->find(1)->toArray();
        $list = di()->get(TableCollector::class)->get('default', 'user');
        foreach ($list as $column) {
            var_dump($column);
        }
        return $this->response->success($data);
    }

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

    public function rel()
    {
        $role = UserRole::query()->with('users.books')->first();

        return $this->response->success($role->toArray());
    }

    public function database()
    {
        /** @var User $user */
        $user = User::find(1);

        var_dump(User::man()->first());
        var_dump($user->book->toArray());

        return $this->response->success($user->toArray());
    }

    public function db()
    {
        $user = \Hyperf\DB\DB::fetch('SELECT * FROM user WHERE id = ?;', [1]);

        return $this->response->success($user);
    }

    public function cache()
    {
        $users = User::findManyFromCache([1, 2, 3]);
        $users = User::findManyFromCache([1, 2, 3]);

        return $this->response->success($users->toArray());
    }

    public function ext()
    {
        $res = UserExt::query()->find(1);

        return $this->response->success($res->toArray());
    }

    public function ext2()
    {
        $model = new UserExt();
        $model->float_num = 1.1;
        $res = $model->save();

        return $this->response->success($res);
    }

    public function rollback()
    {
        $success = $this->request->input('s');

        di()->get(DbService::class)->execute((bool) $success);

        $model = User::query()->where('id', 3)->first();

        return $this->response->success($model);
    }
}
