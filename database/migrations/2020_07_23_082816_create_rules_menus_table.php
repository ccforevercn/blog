<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/7/23
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 规则菜单表
 *
 * Class CreateRulesMenusTable
 */
class CreateRulesMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rules_menus', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->bigIncrements('id')->comment('编号');
            $table->char('unique', 32)->comment('唯一值');
            $table->integer('menu_id')->comment('菜单编号');
            $table->integer('add_time')->comment('添加时间');
            $table->integer('clear_time')->comment('清除时间');
            $table->tinyInteger('is_del')->comment('是否删除 是 1 否 0')->default(0);
            $table->index(['is_del', 'unique']);
            $table->index(['is_del', 'menu_id']);
        });
        (new \App\CcForever\service\Util())->setTableComment('rules_menus', '规则菜单表');  // 设置表备注
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rules_menus');
    }
}
