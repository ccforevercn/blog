<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/7/20
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 管理员表
 *
 * Class CreateAdminsTable
 */
class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->increments('id')->comment('编号');
            $table->char('username', 16)->comment('账号');
            $table->char('password', 32)->comment('密码');
            $table->string('real_name', 16)->comment('昵称');
            $table->tinyInteger('status')->comment('状态 1 正常 0 锁定')->default(1);
            $table->tinyInteger('found')->comment('创建管理员权限 1 是 0 否')->default(1);
            $table->integer('parent_id')->comment('父级管理员编号')->default(0);
            $table->integer('rule_id')->comment('规则编号')->default(0);
            $table->string('email')->comment('邮箱');
            $table->integer('add_time')->comment('添加时间');
            $table->char('add_ip', 15)->comment('添加ip');
            $table->integer('last_time')->comment('最后一次登录时间');
            $table->char('last_ip', 15)->comment('最后一次登录ip');
            $table->integer('login_count')->comment('登录次数')->default(0);
            $table->tinyInteger('is_del')->default(0)->comment('删除状态 1 已删除 0 未删除');
            $table->index(['is_del', 'username']);
            $table->index(['is_del', 'parent_id']);
        });
        (new \App\CcForever\service\Util())->setTableComment('admins', '管理员表');  // 设置表备注
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
