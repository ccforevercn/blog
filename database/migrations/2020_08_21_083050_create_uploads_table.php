<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/8/21
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 上传文件表
 *
 * Class CreateUploadsTable
 */
class CreateUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uploads', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->bigIncrements('id')->comment('编号');
            $table->string('path', 256)->comment('文件地址');
            $table->integer('admin_id')->comment('管理员编号');
            $table->integer('add_time')->comment('添加时间');
            $table->tinyInteger('is_del')->comment('是否删除(1是 0否)');
            $table->index(['is_del', 'admin_id']);
        });
        (new \App\CcForever\service\Util())->setTableComment('uploads', '上传文件表');  // 设置表备注
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('uploads');
    }
}
