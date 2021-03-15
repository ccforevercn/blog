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
 * 管理员规则表
 *
 * Class CreateRulesTable
 */
class CreateRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rules', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->bigIncrements('id')->comment('编号');
            $table->string('name', 64)->comment('名称');
            $table->text('menus_id')->comment('菜单编号(多个使用英文逗号隔开)');
            $table->char('unique', 32)->unique()->comment('唯一值(用于规则菜单匹配)');
            $table->integer('admin_id')->comment('管理员编号(哪一个管理员创建的规则)');
            $table->integer('add_time')->comment('添加时间');
            $table->tinyInteger('is_del')->comment('是否删除 1 是 0 否');
            $table->index(['is_del', 'admin_id']);
        });
        (new \App\CcForever\service\Util())->setTableComment('rules', '管理员规则表');  // 设置表备注
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rules');
    }
}
