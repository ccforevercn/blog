<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @date:    2021/3/2
 */

namespace App\CcForever\service;

use App\Exceptions\{Columns as ExceptionsColumns, Caches as ExceptionsCaches, Messages as ExceptionsMessages};
use App\Repositories\{
    BannersRepository, ColumnsRepository, ConfigMessageRepository, LinksRepository, MessagesRepository
};

/**
 * 缓存
 *
 * Class Caches
 * @package App\CcForever\service
 */
class Caches
{
    /**
     * 资源文件根目录
     *
     * @var string
     */
    private $style = 'style/';

    /**
     * 配置信息
     *
     * @var ConfigMessageRepository
     */
    private $config;

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
     * 轮播
     *
     * @var BannersRepository
     */
    private $banners;

    /**
     * 链接
     *
     * @var LinksRepository
     */
    private $links;

    /**
     * 工具
     *
     * @var Util
     */
    private $util;

    public function __construct()
    {
        $this->config = new ConfigMessageRepository();
        $this->columns = new ColumnsRepository();
        $this->messages = new MessagesRepository();
        $this->banners = new BannersRepository();
        $this->links = new LinksRepository();
        $this->util = new Util();
    }

    /**
     * 公共数据
     *
     * @return array
     */
    private function publicRepository(): array
    {
        $configs = $this->config->configs([], true); // 网站配置
        // 验证网站标题是否存在，不存在添加网站标题元素
        $checkConfigSeoTitle = !array_key_exists('config_seo_title', $configs);
        if($checkConfigSeoTitle) { $configs['config_seo_title'] = ''; }
        // 验证网站关键字是否存在，不存在添加网站关键字元素
        $checkConfigSeoKeyword = !array_key_exists('config_seo_keyword', $configs);
        if($checkConfigSeoKeyword) { $configs['config_seo_keyword'] = ''; }
        // 验证网站描述是否存在，不存在添加网站描述元素
        $checkConfigSeoDescription = !array_key_exists('config_seo_description', $configs);
        if($checkConfigSeoDescription) { $configs['config_seo_description'] = ''; }
        // 验证网站内链后缀是否存在，不存在添加网站内链后缀元素
        $checkConfigPageSuffix = !array_key_exists('config_page_suffix', $configs);
        if($checkConfigPageSuffix) { $configs['config_page_suffix'] = '.html'; }
        $navigations = $this->columns->navigation(); // 网站导航
        $banners = $this->banners->banners(); // 网站轮播图
        $links = $this->links->links(); // 网站友情链接
        return compact('configs', 'navigations', 'banners', 'links');
    }

    /**
     * 顶级栏目
     *
     * @param array $column
     * @return array
     */
    private function firstColumn(array $column): array
    {
        $firstColumn = $column; // 默认顶级栏目
        $first = []; // 查询顶级栏目
        // 上级栏目存在重置父级
        $checkParentId = array_key_exists('parent_id', $column) && $column['parent_id'];
        if($checkParentId){ $first = $this->columns->firstColumn($column['id']); }
        if(count($first)){ $firstColumn = $first; }
        return $firstColumn;
    }

    /**
     * 重置网站头部信息(标题、关键字、描述)
     *
     * @param array $public
     * @param string $name
     * @param string $keywords
     * @param string $description
     * @return array
     */
    private function resetWebsiteHeader(array $public, string $name, string $keywords, string $description): array
    {
        $public['configs']['config_seo_title'] = $name.'-'.$public['configs']['config_seo_title']; // 重置网站标题
        $public['configs']['config_seo_keyword'] = $keywords.'-'.$public['configs']['config_seo_keyword']; // 重置网站关键字
        $public['configs']['config_seo_description'] = $description.'-'.$public['configs']['config_seo_description']; // 重置网站描述
        return  $public;
    }

    /**
     * 面包销导航
     *
     * @param array ...$results
     * @return string
     */
    private function crumbs(array ...$results): string
    {
        $crumbs = "<a href='/' title='首页'>首页</a>>"; // 默认面包销导航
        // 追加面包销导航
        foreach ($results as $result) { $crumbs .= "<a href='" . $result['url'] . "' title='" . $result['name'] . "'>" . $result['name'] . "</a>>"; }
        return $crumbs;
    }

    /**
     * 子栏目
     *
     * @param int $columnsId
     * @param array $configs
     * @return array
     */
    private function children(int $columnsId, array $configs): array
    {
        $children = $this->columns->children($columnsId, 0); // 子栏目获取
        foreach ($children as &$child) { $child['url'] = $this->util->url($child, $configs); } // 处理地址
        return $children;
    }

    /**
     * 栏目数据
     *
     * @param array $columnIds
     * @return array
     * @throws ExceptionsCaches
     */
    private function columnsRepository(array $columnIds): array
    {
        try {
            // 验证是否有栏目编号，如果没有则获取全部栏目编号
            if(!count($columnIds)) {
                $columns =  $this->columns->total(['id'], [], [], 0, 0); // 栏目信息
                $columnIds = array_map(function ($item){ return $item['id']; }, $columns); // 获取栏目编号
            }
            if(!count($columnIds)) { return []; } // 暂无栏目编号
            $messages = []; // 默认信息列表
            $page = 1; // 默认页
            $public = $this->publicRepository(); // 公共数据
            $repository = []; // 默认返回数据
            $columns = $this->columns->messages($columnIds); // 栏目信息
            foreach ($columns as $column) {
                // 页面缓存
                if(!$column['render']) {
                    $firstColumn = $this->firstColumn($column);  // 顶级栏目信息
                    $navigationId = $firstColumn['id']; // 导航编号
                    $column['url'] = $this->util->url($column, $public['configs']); // 栏目链接
                    $firstColumn['url'] = $this->util->url($firstColumn, $public['configs']); // 顶级栏目链接
                    $public = $this->resetWebsiteHeader($public, $column['name'], $column['keywords'], $column['description']); // 重置网站头部信息
                    $checkCrumbs = $firstColumn['id'] !== $column['id']; // 验证面包销导航栏目
                    $crumbs = $checkCrumbs ? $this->crumbs($firstColumn, $column) : $this->crumbs($firstColumn); // 面包销导航
                    $children = $this->children($navigationId, $public['configs']); // 子栏目
                    $subsetIds = $this->columns->subsetIds($column['id']); // 当前栏目和下级栏目编号
                    $order = (new Check())->messageOrderBy($column['sort']); // 排序方式
                    try {
                        // 验证当前栏目下是否有信息 如果没有则调用下级排序第一个栏目的信息
                        $count = $this->messages->count(['columns_id' => $subsetIds]);
                        $pageCount = (int)ceil(floatval(bcdiv((string)$count, (string)$column['limit'], 2))); // 总页数
                        // 信息列表存在
                        if($pageCount) {
                            $url = $column['url'];
                            for ($page = 1; $page <= $pageCount; $page++){
                                // 信息列表
                                $messages = $this->messages->lst(['columns_id' => $subsetIds], $order, $page, $column['limit']);
                                // 信息地址
                                foreach ($messages as &$message) { $message['url'] =  $this->util->url($message, $public['configs']); }
                                // 页数大于1时重置栏目地址
                                if($page > 1){$column['url'] =  str_replace($public['configs']['config_page_suffix'],'', $url).'-'.$page.$public['configs']['config_page_suffix'];}
                                // 合并 栏目、当前页、顶级栏目、顶级栏目的子栏目、面包屑导航、信息列表、导航编号 为一个数组
                                $basic = compact('column', 'page', 'firstColumn', 'children', 'crumbs', 'messages', 'navigationId');
                                // 合并公共数据为新的数组
                                $repository[] = array_merge($public, $basic);
                            }
                        }else {
                            // 栏目下没有信息列表
                            $basic = compact('column', 'page', 'firstColumn', 'children', 'crumbs', 'messages', 'navigationId');
                            // 合并公共数据为新的数组
                            $repository[] = array_merge($public, $basic);
                        }
                    }catch (ExceptionsMessages $exception) { throw new ExceptionsCaches($exception->getMessage()); }
                }
            }
        }catch (ExceptionsColumns $exception) { throw new ExceptionsCaches($exception->getMessage()); }
        return  $repository;
    }

    /**
     * 信息数据
     *
     * @param array $columnId
     * @return array
     * @throws ExceptionsCaches
     */
    private function messagesRepository(array $columnId): array
    {
        try {
            // 验证是否有信息编号，如果没有则获取全部信息编号
            if (!count($columnId)) {
                $messages = $this->messages->total(['id']); // 信息
                $messageIds = array_map(function ($item) {return $item['id'];}, $messages); // 获取信息编号
            }else {
                $messages = $this->messages->total(['id'], ['columns_id' => $columnId]); // 信息
                $messageIds = array_map(function ($item) {return $item['id'];}, $messages); // 获取信息编号
            }
            if(!count($messageIds)) { return []; } // 暂无信息编号
            $public = $this->publicRepository(); // 公共数据
            $repository = []; // 默认返回数据
            // 查询字段
            $selects = ['id', 'name', 'columns_id', 'image', 'keywords', 'description', 'update_time', 'writer', 'click', 'page'];
            $messages = $this->messages->messages($selects, ['id' => $messageIds], ['id' => 'DESC'], 0, 0);
            foreach ($messages as &$message) {
                $message['tags'] = explode(',', $message['tag_name']); // 信息标签
                $message['url'] = $this->util->url($message, $public['configs']); // 信息地址
                $column = $this->columns->message($message['columns_id'], []); // 栏目信息
                $column['url'] = $this->util->url($column, $public['configs']); // 栏目地址
                $firstColumn = $this->firstColumn($column); // 顶级栏目
                $firstColumn['url'] = $this->util->url($firstColumn, $public['configs']); // 顶级栏目地址
                $navigationId = $firstColumn['id']; // 导航编号
                $children = $this->children($navigationId, $public['configs']); // 导航子栏目
                $checkCrumbs = $firstColumn['id'] !== $column['id']; // 验证面包销导航栏目
                $crumbs = $checkCrumbs ? $this->crumbs($firstColumn, $column, $message) : $this->crumbs($firstColumn, $message); // 面包销导航
                $public = $this->resetWebsiteHeader($public, $message['name'], $message['keywords'], $message['description']); // 重置网站头部信息
                $param = (new Check())->whereMessagePro($column['sort']); // 排序方式
                $param['message'] = $message[$param['select']]; // 验证条件字段值
                $message['pre'] = $this->messages->paging($message['id'], ['id', 'name', 'page'], $param); // 上一页
                if(count($message['pre'])){ $message['pre']['url'] = $this->util->url($message['pre'], $public['configs']); } // 上一页地址
                $param = (new Check())->whereMessageNext($column['sort']); // 排序方式
                $param['message'] = $message[$param['select']]; // 验证条件字段值
                $message['next'] = $this->messages->paging($message['id'], ['id', 'name', 'page'], $param); // 下一页
                if(count($message['next'])){ $message['next']['url'] = $this->util->url($message['next'], $public['configs']); } // 下一页地址
                $message['update_time'] = date('Y-m-d H:i:s', $message['update_time']); // 格式化修改时间
                $basic = compact('column', 'firstColumn', 'children', 'crumbs', 'message', 'navigationId'); // 基本数据
                // 合并公共数据为新的数组
                $repository[] = array_merge($public, $basic);
            }
        }catch (ExceptionsMessages $exception) { throw new ExceptionsCaches($exception->getMessage()); }
        return  $repository;
    }

    /**
     * 搜索数据
     *
     * @return array
     * @throws ExceptionsCaches
     */
    private function searchRepository(): array
    {
        $public = $this->publicRepository(); // 公共数据
        $navigationId = -1; // 导航编号
        try {
            $selects = ['id', 'name', 'image', 'keywords', 'description', 'click', 'update_time', 'writer', 'page']; // 查询字段
            $messages = $this->messages->messages($selects, [], [], 0, 0); // 信息列表
            foreach ($messages as &$message) {
                $message['url'] = $this->util->url($message, $public['configs']); // 信息地址
                $message['time'] = date('Y-m-d', $message['update_time']); // 信息地址
            }

        }catch (ExceptionsMessages $exception) { throw new ExceptionsCaches($exception->getMessage()); }
        return array_merge($public, compact('navigationId', 'messages'));
    }

    /**
     * 首页
     *
     * @return array
     * @throws ExceptionsCaches
     */
    public function index(): array
    {
        try {
            $index = $this->publicRepository(); // 公共数据
            $resources = $this->style.'index';
            $target = 'index'.$index['configs']['config_page_suffix']; // 目标文件
            self::write($resources, $target, array_merge($index, ['navigationId' => 0]));
            return [$target];
        }catch (ExceptionsCaches $exception) {
            throw new ExceptionsCaches($exception->getMessage());
        }
    }

    /**
     * 栏目
     *
     * @param int $columnId
     * @return array
     * @throws ExceptionsCaches
     */
    public function columns(int $columnId): array
    {
        try {
            $writes = []; // 生成文件列表
            switch ($columnId) {
                case true: $columns = $this->columnsRepository([$columnId]); break; // 缓存指定栏目
                default: $columns = $this->columnsRepository([]); // 所有栏目缓存
            }
            foreach ($columns as &$column){
                $this->folder($column['column']['url']);
                $resources = $this->style.$column['column']['page']; // 资源文件
                // 目标文件  清除地址第一个字符/
                $target = substr($column['column']['url'], 1, strlen($column['column']['url']));
                $writes[] = $target; // 向生成文件列表中插入目标文件
                self::write($resources, $target, $column);
            }
            return $writes;
        }catch (ExceptionsCaches $exception) {
            throw new ExceptionsCaches($exception->getMessage());
        }
    }

    /**
     * 信息
     *
     * @param int $columnId
     * @return array
     * @throws ExceptionsCaches
     */
    public function messages(int $columnId): array
    {
        try {
            $writes = []; // 生成文件列表
            switch ($columnId) {
                case true: $messages = $this->messagesRepository([$columnId]); break; // 缓存指定信息
                default: $messages = $this->messagesRepository([]); // 所有信息缓存
            }
            foreach ($messages as &$message){
                $this->folder($message['message']['url']);
                $resources = $this->style.$message['message']['page']; // 资源文件
                // 目标文件  清除地址第一个字符/
                $target = substr($message['message']['url'], 1, strlen($message['message']['url']));
                $writes[] = $target; // 向生成文件列表中插入目标文件
                self::write($resources, $target, $message);
            }
            return $writes;
        }catch (ExceptionsCaches $exception) {
            throw new ExceptionsCaches($exception->getMessage());
        }
    }

    /**
     * 搜索页
     *
     * @return array
     * @throws ExceptionsCaches
     */
    public function search(): array
    {
        try {
            $search = $this->searchRepository();  // 缓存数据
            $messages = json_encode($search['messages'], JSON_UNESCAPED_UNICODE); // 信息转为json
            $script = "var data = [];var html = '';function getQuerySelect(select){var query = window.location.search.substring(1);var selects = query.split(\"&\");for(var i = 0; i < selects.length; i++){var param = selects[i].split(\"=\");if (param[0] === select){return param[1];}}return false;}function search(){var name = decodeURIComponent(getQuerySelect('search'));if(name.length < 1 || name === false){document.getElementById('search').innerHTML = '暂无搜索内容';}else{document.getElementById('search').innerHTML = '搜索【' + decodeURIComponent(getQuerySelect('search')) + '】';for (let index in messages){if(messages[index].name.indexOf(name) >= 0){data.push(messages[index])}}}};";
            $searchFile = 'search.js';
            $script .= 'search();
            for (var index in data) {
                html += \'<li class="clearfix">\';
                html += \'<div class="l-img fl">\';
                html += \'<a class="new_img" href="\' + data[index].url + \'" title="\' + data[index].name + \'">\';
                html += \'<img src="\' + data[index].image + \'" alt="\' + data[index].name + \'">\';
                html += \'</div>\';
                html += \'<a class="tag hidden-sm-md-lg" href="\' + data[index].url + \'" title="\' + data[index].name + \'">\' + data[index].name + \'</a>\';
                html += \'</div>\';
                html += \'<div class="main_news fr">\';
                html += \'<h3><a href="\' + data[index].url + \'" title="\' + data[index].name + \'">\' + data[index].name + \'</a></h3>\';
                html += \'<p class="main_article m-multi-ellipsis">\' + data[index].description.substring(0,240) + \'</p>\';
                html += \'<p class="meta"> <span class="hidden-sm-md-lg"><i class="fa fa-user-circle"></i></span><span><i class="fa fa-eye"></i>\' + data[index].click + \'</span><span><i class="fa fa-clock-o"></i>\' + data[index].time + \'</span> </p>\';
                html += \'</div>\';
                html += \'</li>\';
            }
        document.getElementById(\'search-list\').insertAdjacentHTML(\'beforeend\', html);';
            $this->folder('/resources/search/'); // 创建目标search.js文件夹
            $searchPath = public_path('resources'.DIRECTORY_SEPARATOR.'search'.DIRECTORY_SEPARATOR);  // 目标search.js文件夹
            $file = @fopen($searchPath.$searchFile, 'w+'); // 打开search.js文件夹
            fwrite($file, 'var messages = '.$messages.";".$script); // 写入数据
            fclose($file); // 关闭文件
            $resources = $this->style.'search'; // 资源文件
            $target = 'search'.$search['configs']['config_page_suffix']; // 目标文件
            $writes[] = $target; // 向生成文件列表中插入目标文件
            self::write($resources, $target, $search);
            // 缓存成功添加search.js到搜索页面
            $tpl = public_path($target);
            $file = fopen($tpl, 'r');
            $searchContent = fread($file, filesize($tpl));
            fclose($file);
            $searchContent = str_replace("</body>", "<script type='text/javascript' src=\"/resources/search/".$searchFile."\"></script>\r\n</body>", $searchContent);
            $file = @fopen($tpl, 'w+');
            fwrite($file, $searchContent);
            fclose($file);
            return $writes;
        }catch (ExceptionsCaches $exception) {
            throw new ExceptionsCaches($exception->getMessage());
        }
    }

    /**
     * 文件夹创建
     *
     * @param string $folder
     * @throws ExceptionsCaches
     */
    private function folder(string $folder): void
    {
        $separator = '/'; // 分隔符
        $folders = explode($separator, $folder); // 切割为数组
        array_shift($folders); // 去掉第一个元素  空串
        array_pop($folders); // 去掉最后一个文件
        $total = ''; // 全部文件夹
        $status = true; // 文件夹创建状态
        foreach ($folders as $key=>$dir) {
            $dir = $total.$dir; // 重置验证文件夹
            if(!is_dir($dir)) { $status = $status && mkdir($dir); } // 验证文件夹是否存在，不存在则创建
            $total .= $dir.DIRECTORY_SEPARATOR; // 追加文件夹
        }
        if(!$status){ throw new ExceptionsCaches("目标文件夹创建失败"); }
    }

    /**
     * 静态页面
     *
     * @param string $resources
     * @param string $target
     * @param array $repository
     * @throws ExceptionsCaches
     */
    private function write(string $resources, string $target, array $repository): void
    {
        try{
            $string = view($resources, $repository)->toHtml(); // 页面HTML
            $file = fopen($target, 'w+'); // 打开目标文件
            fwrite($file, $string); // 把HTML写入文件中
            fclose($file); // 关闭文件
        }catch (\Exception $exception){
            throw new ExceptionsCaches($exception->getMessage());
        }
    }
}
