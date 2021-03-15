<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/8/4
 */

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * 视图表默认数据
 *
 * Class ViewsTableSeeder
 */
class ViewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('views')->insert([
            [
                'id' => 1,
                'name' => '列表',
                'path' => 'ccnews',
                'add_time' => time(),
                'type' => 1,
                'is_del' => 0,
            ],
            [
                'id' => 2,
                'name' => '详情',
                'path' => 'ccnew',
                'add_time' => time(),
                'type' => 2,
                'is_del' => 0,
            ]
        ]);
    }
}
