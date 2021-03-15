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
 * 信息内容表
 *
 * Class CreateMessagesContentTable
 */
class CreateMessagesContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages_content', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->integer('id')->primary()->comment('编号');
            $table->longText('content')->comment('html')->default('')->nullable(false);
            $table->longText('markdown')->comment('md')->default('')->nullable(false);
            $table->tinyInteger('is_del')->comment('是否删除 1是 0否')->default(1);
        });
        (new \App\CcForever\service\Util())->setTableComment('messages_content', '信息内容表');  // 设置表备注
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages_content');
    }
}
