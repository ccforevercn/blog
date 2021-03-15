<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/8/5
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 轮播图表
 *
 * Class CreateBannersTable
 */
class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->bigIncrements('id')->comment('编号');
            $table->string('name', 128)->comment('名称');
            $table->string('link', 128)->comment('链接');
            $table->string('image', 128)->comment('图片');
            $table->integer('weight')->comment('权重');
            $table->integer('add_time')->comment('添加时间');
            $table->tinyInteger('is_del')->comment('是否删除(1是 0否)');
            $table->index(['is_del']);
        });
        (new \App\CcForever\service\Util())->setTableComment('banners', '轮播图表');  // 设置表备注
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banners');
    }
}
