<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/7/31
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 信息表
 *
 * Class CreateMessagesTable
 */
class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->bigIncrements('id')->comment('编号');
            $table->string('name', 255)->comment('名称')->default('名称');
            $table->integer('columns_id')->comment('栏目编号')->default(0)->nullable();
            $table->string('image', 128)->comment('图片')->default('')->nullable();
            $table->string('writer', 32)->comment('作者')->default('');
            $table->integer('click')->comment('点击量')->default(1);
            $table->integer('weight')->comment('权重')->default(1);
            $table->string('keywords', 512)->comment('关键字')->default('关键字');
            $table->string('description', 1024)->comment('描述')->default('描述');
            $table->tinyInteger('index')->comment('首页推荐(1是 0否)')->default(1);
            $table->tinyInteger('hot')->comment('热门推荐(1是 0否)')->default(1);
            $table->integer('add_time')->comment('添加时间');
            $table->integer('update_time')->comment('修改时间');
            $table->char('page', 32)->comment('信息页面');
            $table->char('unique', 32)->unique()->comment('标签唯一值');
            $table->tinyInteger('is_del')->comment('是否删除(1是 0否)')->default(0);
            $table->index(['is_del', 'columns_id', 'index', 'hot']);
        });
        (new \App\CcForever\service\Util())->setTableComment('messages', '信息表');  // 设置表备注
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
