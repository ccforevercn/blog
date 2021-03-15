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
 * 配置信息表
 *
 * Class CreateConfigMessageTable
 */
class CreateConfigMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config_message', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->bigIncrements('id')->comment('编号');
            $table->string('name', 128)->comment('名称');
            $table->string('description', 256)->comment('描述');
            $table->char('select', 32)->comment('唯一值');
            $table->tinyInteger('type')->comment('类型 (1 文本框 2 单选 3 多选 4 图片 5 多行文本框)');
            $table->string('type_value', 256)->comment('类型值(单选/多选 格式 field:value|field:value|field:value|field:value...)');
            $table->text('value')->comment('值');
            $table->integer('category_id')->comment('配置分类编号');
            $table->integer('add_time')->comment('添加时间');
            $table->tinyInteger('is_show')->comment('是否展示(传到模板1是 0否)');
            $table->tinyInteger('is_del')->comment('是否删除(1是 0否)');
            $table->index(['is_del', 'category_id']);
            $table->index(['is_del', 'select', 'is_show']);
        });
        (new \App\CcForever\service\Util())->setTableComment('config_message', '配置信息表');  // 设置表备注
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('config_message');
    }
}
