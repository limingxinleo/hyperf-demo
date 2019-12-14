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

use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

class CreateGroupUserTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('group_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('user_id');
            $table->enum(
                'role',
                ['creator', 'guest', 'partner', 'member', 'black']
            )->default('member')->comment('圈子用户角色');
            $table->unsignedTinyInteger('level')->default(1)->comment('用户等级');
            $table->boolean('status')->default(true)->comment('用户状态');
            $table->dateTime('member_at')->nullable()->comment('成为圈子成员的时间');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['group_id', 'user_id', 'role']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_user');
    }
}
