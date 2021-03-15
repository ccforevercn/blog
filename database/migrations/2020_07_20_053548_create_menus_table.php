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
 * 菜单表
 *
 * Class CreateMenusTable
 */
class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->increments('id')->comment('编号');
            $table->string('name', 64)->comment('名称');
            $table->integer('parent_id')->default(0)->comment('父级按钮编号');
            $table->string('routes', 64)->comment('路由');
            $table->string('page', 64)->default('')->comment('页面');
            $table->string('icon', 16)->nullable()->default('')->comment('样式');
            $table->tinyInteger('menu')->default(1)->comment('菜单状态 1 页面菜单 0 路由菜单');
            $table->tinyInteger('is_del')->default(0)->comment('删除状态 1 已删除 0 未删除');
            $table->integer('add_time')->comment('添加时间');
            $table->integer('sort')->default(1)->comment('排序');
            $table->index(['is_del', 'routes']);
            $table->index(['is_del', 'menu']);
            $table->index(['is_del', 'parent_id']);
        });
        (new \App\CcForever\service\Util())->setTableComment('menus', '菜单表');  // 设置表备注
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
