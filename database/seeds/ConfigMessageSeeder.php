<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/9/1
 */

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * 配置信息表默认数据
 *
 * Class ConfigMessageSeeder
 */
class ConfigMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('config_message')->insert([
            [
                'id' => 1,
                'name' => '名称',
                'description' => '网站名称',
                'select' => 'config_name',
                'type' => 1,
                'type_value' => '',
                'value' => '',
                'category_id' => 1,
                'add_time' => time(),
                'is_show' => 1,
                'is_del' => 0
            ],
            [
                'id' => 2,
                'name' => '别名',
                'description' => '网站别名',
                'select' => 'config_name_alias',
                'type' => 1,
                'type_value' => '',
                'value' => '',
                'category_id' => 1,
                'add_time' => time(),
                'is_show' => 1,
                'is_del' => 0
            ],
            [
                'id' => 3,
                'name' => '域名',
                'description' => '网站域名',
                'select' => 'config_domain_name',
                'type' => 1,
                'type_value' => '',
                'value' => '',
                'category_id' => 1,
                'add_time' => time(),
                'is_show' => 1,
                'is_del' => 0
            ],
            [
                'id' => 4,
                'name' => 'logo',
                'description' => '网站logo',
                'select' => 'config_logo',
                'type' => 4,
                'type_value' => '',
                'value' => '',
                'category_id' => 1,
                'add_time' => time(),
                'is_show' => 1,
                'is_del' => 0
            ],
            [
                'id' => 5,
                'name' => '底部logo',
                'description' => '网站底部logo',
                'select' => 'config_bottom_logo',
                'type' => 4,
                'type_value' => '',
                'value' => '',
                'category_id' => 1,
                'add_time' => time(),
                'is_show' => 1,
                'is_del' => 0
            ],
            [
                'id' => 6,
                'name' => '版权',
                'description' => '网站版权(支持html标签)',
                'select' => 'config_copyright',
                'type' => 5,
                'type_value' => '',
                'value' => '',
                'category_id' => 1,
                'add_time' => time(),
                'is_show' => 1,
                'is_del' => 0
            ],
            [
                'id' => 7,
                'name' => '备案号',
                'description' => '网站备案号(支持html标签)',
                'select' => 'config_record_number',
                'type' => 5,
                'type_value' => '',
                'value' => '',
                'category_id' => 1,
                'add_time' => time(),
                'is_show' => 1,
                'is_del' => 0
            ],
            [
                'id' => 8,
                'name' => '头部代码',
                'description' => 'head标签内展示,支持style、script等标签',
                'select' => 'config_header_code',
                'type' => 5,
                'type_value' => '',
                'value' => '',
                'category_id' => 1,
                'add_time' => time(),
                'is_show' => 1,
                'is_del' => 0
            ],
            [
                'id' => 9,
                'name' => '底部代码',
                'description' => 'body标签结之前,支持html标签',
                'select' => 'config_footer_code',
                'type' => 5,
                'type_value' => '',
                'value' => '',
                'category_id' => 1,
                'add_time' => time(),
                'is_show' => 1,
                'is_del' => 0
            ],
            [
                'id' => 10,
                'name' => '标题',
                'description' => '首页标题、栏目页面和信息页面标题后缀，建议不超过80个字符',
                'select' => 'config_seo_title',
                'type' => 1,
                'type_value' => '',
                'value' => '',
                'category_id' => 2,
                'add_time' => time(),
                'is_show' => 1,
                'is_del' => 0
            ],
            [
                'id' => 11,
                'name' => '关键字',
                'description' => '首页关键字、栏目页面关键字后缀，建议不超过100个字符',
                'select' => 'config_seo_keyword',
                'type' => 1,
                'type_value' => '',
                'value' => '',
                'category_id' => 2,
                'add_time' => time(),
                'is_show' => 1,
                'is_del' => 0
            ],
            [
                'id' => 12,
                'name' => '描述',
                'description' => '首页描述、栏目页面描述后缀，建议不超过200个字符',
                'select' => 'config_seo_description',
                'type' => 5,
                'type_value' => '',
                'value' => '',
                'category_id' => 2,
                'add_time' => time(),
                'is_show' => 1,
                'is_del' => 0
            ],
            [
                'id' => 13,
                'name' => '热搜词',
                'description' => '网站热搜词(多个请用|隔开，支持html标签)',
                'select' => 'config_heat_word',
                'type' => 5,
                'type_value' => '',
                'value' => '',
                'category_id' => 2,
                'add_time' => time(),
                'is_show' => 1,
                'is_del' => 0
            ],
            [
                'id' => 14,
                'name' => '内链后缀',
                'description' => '网站生成链接的后缀',
                'select' => 'config_page_suffix',
                'type' => 5,
                'type_value' => '',
                'value' => '.html',
                'category_id' => 2,
                'add_time' => time(),
                'is_show' => 1,
                'is_del' => 0
            ],
            [
                'id' => 15,
                'name' => '链接优先级',
                'description' => '网站地图XML中的内链和外链的优先级',
                'select' => 'config_xml_priority',
                'type' => 2,
                'type_value' => '0.8:高|0.6:中|0.4:低',
                'value' => '0.6',
                'category_id' => 2,
                'add_time' => time(),
                'is_show' => 0,
                'is_del' => 0
            ],
            [
                'id' => 16,
                'name' => '内容修改频率',
                'description' => '网站内容修改频率',
                'select' => 'config_xml_rate',
                'type' => 2,
                'type_value' => 'always:随时|hourly:每小时|daily:每天|weekly:每周|monthly:每月|yearly:每年|never:永久',
                'value' => 'monthly',
                'category_id' => 2,
                'add_time' => time(),
                'is_show' => 0,
                'is_del' => 0
            ],
            [
                'id' => 17,
                'name' => '姓名',
                'description' => '站长昵称',
                'select' => 'config_service_name',
                'type' => 1,
                'type_value' => '',
                'value' => '',
                'category_id' => 3,
                'add_time' => time(),
                'is_show' => 1,
                'is_del' => 0
            ],
            [
                'id' => 18,
                'name' => '电话',
                'description' => '站长电话',
                'select' => 'config_service_phone',
                'type' => 1,
                'type_value' => '',
                'value' => '',
                'category_id' => 3,
                'add_time' => time(),
                'is_show' => 1,
                'is_del' => 0
            ],
            [
                'id' => 19,
                'name' => 'QQ',
                'description' => '站长QQ',
                'select' => 'config_service_qq',
                'type' => 1,
                'type_value' => '',
                'value' => '',
                'category_id' => 3,
                'add_time' => time(),
                'is_show' => 1,
                'is_del' => 0
            ],
            [
                'id' => 20,
                'name' => '邮箱',
                'description' => '站长邮箱',
                'select' => 'config_service_email',
                'type' => 1,
                'type_value' => '',
                'value' => '',
                'category_id' => 3,
                'add_time' => time(),
                'is_show' => 1,
                'is_del' => 0
            ],
            [
                'id' => 21,
                'name' => '微信',
                'description' => '站长微信号',
                'select' => 'config_service_wechat',
                'type' => 1,
                'type_value' => '',
                'value' => '',
                'category_id' => 3,
                'add_time' => time(),
                'is_show' => 1,
                'is_del' => 0
            ]
        ]);
    }
}
