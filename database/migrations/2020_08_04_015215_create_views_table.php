<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/8/4
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 视图表
 *
 * Class CreateViewsTable
 */
class CreateViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('views', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->bigIncrements('id')->comment('编号');
            $table->string('name', 32)->comment('名称');
            $table->string('path', 16)->comment('地址');
            $table->integer('add_time')->comment('添加时间');
            $table->tinyInteger('type')->comment('视图类型 1 栏目 2 文章');
            $table->tinyInteger('is_del')->comment('是否删除 1 是 0 否');
            $table->index(['is_del', 'type']);
        });
        (new \App\CcForever\service\Util())->setTableComment('views', '视图表');  // 设置表备注
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('views');
    }
}
