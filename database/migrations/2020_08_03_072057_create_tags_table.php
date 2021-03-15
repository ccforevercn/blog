<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/8/3
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 标签表
 *
 * Class CreateTagsTable
 */
class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->bigIncrements('id')->comment('编号');
            $table->string('name', 32)->comment('名称');
            $table->integer('add_time')->comment('添加时间');
            $table->tinyInteger('status')->comment('状态 1 展示 0 隐藏');
            $table->tinyInteger('is_del')->comment('是否删除 1 是 0 否');
            $table->index(['is_del', 'name']);
            $table->index(['is_del', 'status']);
        });
        (new \App\CcForever\service\Util())->setTableComment('tags', '标签表');  // 设置表备注
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tags');
    }
}
