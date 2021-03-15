<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('board', function (Blueprint $table) {
            $table->engine = 'InnoDB'; // 表存储引擎
            $table->charset = 'utf8';  //表默认字符集
            $table->collation = 'utf8_general_ci';  // 表默认的排序规则
            $table->bigIncrements('id')->comment('编号');
            $table->char('speak', 16)->comment('留言者');
            $table->string('content', 512)->comment('留言内容');
            $table->integer('add_time')->comment('留言时间');
            $table->tinyInteger('is_del')->comment('是否删除(1是 0否)');
        });
        (new \App\CcForever\service\Util())->setTableComment('board', '留言板表');  // 设置表备注
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('board');
    }
}
