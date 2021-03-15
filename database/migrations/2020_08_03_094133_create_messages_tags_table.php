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
 * 信息标签表
 *
 * Class CreateMessagesTagsTable
 */
class CreateMessagesTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages_tags', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->bigIncrements('id')->comment('编号');
            $table->integer('tag_id')->comment('标签编号');
            $table->char('unique', 32)->comment('唯一值(信息唯一值对应)');
            $table->integer('add_time')->comment('添加时间');
            $table->integer('clear_time')->comment('清除时间');
            $table->tinyInteger('is_del')->comment('是否删除 1 是 0 否');
            $table->index(['is_del', 'unique']);
            $table->index(['is_del', 'tag_id']); // 标签状态普通索引
        });
        (new \App\CcForever\service\Util())->setTableComment('messages_tags', '信息标签表');  // 设置表备注
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages_tags');
    }
}
