<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:    2020/7/31
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 栏目表
 *
 * Class CreateColumnsTable
 */
class CreateColumnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('columns', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->bigIncrements('id')->comment('编号');
            $table->string('name', 256)->comment('名称')->default('名称');
            $table->string('name_alias', 256)->comment('别名')->default('别名');
            $table->integer('parent_id')->comment('父级栏目编号')->default(0)->nullable();
            $table->string('image', 128)->comment('图片')->default('')->nullable();
            $table->string('banner_image', 128)->comment('轮播图片')->default('')->nullable();
            $table->string('keywords', 512)->comment('关键字')->default('关键字');
            $table->string('description', 1024)->comment('描述')->default('描述');
            $table->integer('weight')->comment('权重')->default(1);
            $table->integer('limit')->comment('信息分页 为0时信息栏目列表页不查询')->default(10);
            $table->tinyInteger('sort')->comment('信息排序 0 默认编号倒叙 1 修改时间升序 2 修改时间倒叙 3 权重升序 4 权重倒叙 5 点击量升序 6 点击量降序')->default(0);
            $table->tinyInteger('navigation')->comment('导航状态(1是 0否)')->default(0);
            $table->tinyInteger('render')->comment('渲染类型(1超链 0页面)')->default(0);
            $table->string('page', 128)->comment('页面/链接');
            $table->integer('add_time')->comment('添加时间');
            $table->tinyInteger('is_del')->comment('是否删除(1是 0否)')->default(0);
            $table->index(['is_del', 'parent_id']);
            $table->index(['is_del', 'navigation']);
        });
        (new \App\CcForever\service\Util())->setTableComment('columns', '栏目表');  // 设置表备注
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('columns');
    }
}
