<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @date:    2021/3/4
 */

namespace App\CcForever\service;

use App\Exceptions\{Messages as ExceptionsMessages, Tags as ExceptionsTags};
use App\Repositories\{ColumnsRepository, ConfigMessageRepository, MessagesRepository, TagsRepository};

/**
 * 数据服务
 *
 * Class Repository
 * @package App\CcForever\service
 */
class Repository
{

    /**
     * 栏目
     *
     * @var ColumnsRepository
     */
    private $columns;

    /**
     * 信息
     *
     * @var MessagesRepository
     */
    private $messages;

    /**
     * 配置
     *
     * @var ConfigMessageRepository
     */
    private $config;

    /**
     * 标签
     *
     * @var TagsRepository
     */
    private $tags;

    public function __construct()
    {
        $this->columns = new ColumnsRepository();
        $this->messages = new MessagesRepository();
        $this->config = new ConfigMessageRepository();
        $this->tags = new TagsRepository();
    }

    /**
     * 栏目信息
     *
     * @param int $columnId
     * @param array $selects
     * @return array
     */
    public function column(int $columnId, array $selects): array
    {
        $selects = array_merge($selects, ['page', 'id', 'render']);  // 添加必要参数
        $selects = array_flip(array_flip($selects)); // 删除重复查询字段
        $column = $this->columns->message($columnId, $selects); // 获取栏目列表
        $config = $this->config->config('config_page_suffix'); // 获取配置
        $column['url'] = (new Util())->url($column, ['config_page_suffix' => $config]);
        return $column; // 返回
    }
    /**
     * 信息列表
     *
     * @param int $columnId
     * @param int $page
     * @param int $limit
     * @param array $order
     * @param int $type
     * @return array
     */
    public function messages(int $columnId, int $page, int $limit, array $order, int $type): array
    {
        $where = [];
        // 栏目编号
        if($columnId) { $where['columns_id'] = $columnId; }
        // 信息类型 1 首页推荐 2 热门推荐
        switch ($type) {
            case 1: $where['index'] = 1;break;
            case 2: $where['hot'] = 1; break;
            default:;
        }
        try {
            // 获取配置
            $config = $this->config->config('config_page_suffix');
            // 获取列表
            $lists = $this->messages->lst($where, $order, $page, $limit);
            // 地址处理
            foreach ($lists  as &$list){ $list['url'] = (new Util())->url($list, ['config_page_suffix' => $config]); }
            return $lists; // 返回
        }catch (ExceptionsMessages $exception){
            return [];
        }
    }

    /**
     * 标签
     *
     * @return array
     */
    public function tags(): array
    {
        return $this->tags->total();
    }

    /**
     * 统计
     *
     * 信息总数      message
     * 信息查看总数  click
     * 标签总数      tag
     *
     *
     * @return array
     */
    public function statistics(): array
    {
        $messages = $this->messages->total(['click']);
        $message = count($messages); // 信息总数
        $click = 0; // 信息查看总数
        foreach ($messages as $number) {
            $click += $number['click'];
        }
        try {
            $tag = $this->tags->count([]);
        }catch (ExceptionsTags $exception) {
            $tag = 0; // 标签总数
        }
        return compact('message', 'click', 'tag');
    }

    /**
     * 栏目分页
     *
     * $id 栏目编号
     * $current 当前页
     * $classUl ul标签样式
     * $classLi li标签样式
     * $classA a标签样式
     * $classCurrentLi 当前页li标签样式
     * $classCurrentA 当前页a标签样式
     * $extremes 是否展示首页和尾页(默认true)   true 是   false 否
     * $enter 是否展示上一页和下一页(默认true)   true 是   false 否
     * $number 是否展示之前一页，之前两页，之后一页，之后两页(默认true)   true 是   false 否
     *
     * @param int $id
     * @param int $current
     * @param string $classUl
     * @param string $classLi
     * @param string $classA
     * @param string $classCurrentLi
     * @param string $classCurrentA
     * @param bool $extremes
     * @param bool $enter
     * @param bool $number
     * @return string
     */
    public function page(int $id, int $current, string $classUl = '', string $classLi = '', string $classA = '', string $classCurrentLi = '', string $classCurrentA = '', bool $extremes = true, bool $enter = true, bool $number = true): string
    {
        $page = ''; // 返回的字符串
        $column = $this->columns->message($id, []); // 当前栏目信息
        if(!count($column)) { return $page; } // 栏目不存在
        $pageSuffix = $this->config->config('config_page_suffix'); // 静态页面后缀
        $column['url'] = (new Util())->url($column, ['config_page_suffix' => $pageSuffix]); // 栏目链接
        $subsetIds = $this->columns->subsetIds($id);  // 子栏目编号和当前栏目编号
        // 获取子栏目编号和当前栏目编号的信息总数
        try {$count = $this->messages->count(['columns_id' => $subsetIds]);}
        catch (ExceptionsMessages $exception){$count = 0;}
        $pageCount = (int)ceil(floatval(bcdiv((string)$count, (string)$column['limit'], 2))); // 总页数
        if(!$pageCount) { return $page; }
        $url = $column['url']; // 当前栏目首页
        $page .= '<ul class="'. $classUl .'">';
        $page .= $extremes ? $this->pageFirst($url, $column['name'], $classLi, $classA) : ''; // 首页
        $page .= $enter ? $this->pageBefore($current, 1, $url, $column['name'], $classLi, $classA, '上一页', $pageSuffix) : ''; // 是否展示上一页
        if($number){
            $page .= $this->pageBefore($current, 2, $url, $column['name'], $classLi, $classA, '', $pageSuffix); // 之前两页
            $page .= $this->pageBefore($current, 1, $url, $column['name'], $classLi, $classA, '', $pageSuffix); // 之前一页
        }
        // 当前页
        $page .= '<li class="'. $classCurrentLi .'"><a class="'. $classCurrentA .'" href="javascript:void(0);" title="'. $column['name'] .'">当前页</a></li>';
        // 是否展示之前一页，之前两页，之后一页，之后两页
        if($number) {
            // 之后一页
            $page .= $this->pageAfter($pageCount, $current, 1, $url, $column['name'], $classLi, $classA, '', $pageSuffix);
            // 之后二页
            $page .= $this->pageAfter($pageCount, $current, 2, $url, $column['name'], $classLi, $classA, '', $pageSuffix);
        }
        // 是否展示上一页和下一页
        if($enter) {
            // 下一页
            $page .= $this->pageAfter($pageCount, $current, 1, $url, $column['name'], $classLi, $classA, '下一页', $pageSuffix);
        }
        // 是否展示首页和尾页
        if($extremes) {
            // 尾页
            $page .= $this->pageLast($pageCount, $url, $column['name'], $classLi, $classA, $pageSuffix);
        }
        $page .= '</ul>';
        return $page;
    }

    /**
     * 分页--首页
     *
     * $url 首页地址
     * $title 栏目名称
     * $classLi li标签样式
     * $classA a标签样式
     *
     * @param string $url
     * @param string $title
     * @param string $classLi
     * @param string $classA
     * @return string
     */
    private function pageFirst(string $url, string $title, string $classLi, string $classA): string
    {
        return '<li class="'. $classLi .'"><a class="'. $classA .'" href="'.$url.'" title="'. $title .'">首页</a></li>';
    }

    /**
     * 分页--尾页
     *
     * $url 首页地址
     * $title 栏目名称
     * $classLi li标签样式
     * $classA a标签样式
     *
     * @param int $countPage
     * @param string $url
     * @param string $title
     * @param string $classLi
     * @param string $classA
     * @param string $pageSuffix
     * @return string
     */
    private function pageLast(int $countPage, string $url, string $title, string $classLi, string $classA, string $pageSuffix):string
    {
        if($countPage === 1){
            return '<li class="'. $classLi .'"><a class="'. $classA .'" href="'.$url.'" title="'. $title .'">尾页</a></li>';
        }else{
            $url = str_replace($pageSuffix,'', $url).'-'.$countPage.$pageSuffix;
            return '<li class="'. $classLi .'"><a class="'. $classA .'" href="'.$url.'" title="'. $title .'">尾页</a></li>';
        }
    }

    /**
     *
     * 分页--之前页
     *
     * $current 当前页
     * $page 之前页( 1 之前一页  2 之前2页 ....)
     * $url 首页地址
     * $title 栏目名称
     * $classLi li标签样式
     * $classA a标签样式
     * $name 页面名称
     * $pageSuffix 页面后缀
     *
     * @param int $current
     * @param int $page
     * @param string $url
     * @param string $title
     * @param string $classLi
     * @param string $classA
     * @param string $name
     * @param string $pageSuffix
     * @return string
     */
    private function pageBefore(int $current, int $page, string $url, string $title, string $classLi, string $classA, string $name, string $pageSuffix):string
    {
        $result = '';
        $before = (int)bcsub($current, $page, 0);
        $name = strlen($name) ? $name : $before;
        if($before > 0){
            if($before === 1){
                $result .= '<li class="'. $classLi .'"><a class="'. $classA .'" href="'. $url .'" title="'.$title .'">'. $name .'</a></li>';
            }else{
                $url = str_replace($pageSuffix,'', $url).'-'.$before.$pageSuffix;
                $result .= '<li class="'. $classLi .'"><a class="'. $classA .'" href="'. $url .'" title="'. $title .'">'. $name .'</a></li>';
            }
        }
        return $result;
    }

    /**
     * 分页--之后页
     *
     * $countPage 总页数
     * $current 当前页
     * $page 之后页( 1 之后一页  2 之后2页 ....)
     * $url 首页地址
     * $title 栏目名称
     * $classLi li标签样式
     * $classA a标签样式
     * $name 页面名称
     * $pageSuffix 页面后缀
     *
     * @param int $countPage
     * @param int $current
     * @param int $page
     * @param string $url
     * @param string $title
     * @param string $classLi
     * @param string $classA
     * @param string $name
     * @param string $pageSuffix
     * @return string
     */
    private function pageAfter(int $countPage, int $current, int $page, string $url, string $title, string $classLi, string $classA, string $name, string $pageSuffix): string
    {
        $string = '';
        $after = (int)bcadd((string)$current, (string)$page, 0);
        $name = strlen($name) ? $name : $after;
        if($after <= $countPage){
            $url = str_replace($pageSuffix,'', $url).'-'.$after.$pageSuffix;
            $string .= '<li class="'. $classLi .'"><a class="'. $classA .'" href="'. $url .'" title="'. $title .'">'. $name .'</a></li>';
        }
        return $string;
    }


}
