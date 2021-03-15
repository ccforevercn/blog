<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @date:    2021/2/27
 */

namespace App\CcForever\service;

use App\Repositories\{
    ColumnsRepository, MessagesRepository, ConfigMessageRepository
};
use App\Exceptions\SiteMap as ExceptionsSiteMap;


/**
 * 网站地图
 *
 * Class SiteMap
 * @package App\CcForever\service
 */
class SiteMap
{
    /**
     * html文件名称
     *
     * @var string
     */
    private $html = 'sitemap.html';

    /**
     * xml文件名称
     *
     * @var string
     */
    private $xml = 'sitemap.xml';

    /**
     * txt文件名称
     *
     * @var string
     */
    private $txt = 'sitemap.txt';

    /**
     * 配置
     *
     * @var ConfigMessageRepository
     */
    private $configMessage;

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

    public function __construct()
    {
        $this->configMessage = new ConfigMessageRepository();
        $this->columns = new ColumnsRepository();
        $this->messages = new MessagesRepository();
    }

    /**
     * url
     *
     * @return array
     */
    public function urls(): array
    {
        $configs = $this->configMessage->configs(['config_domain_name', 'config_page_suffix'], false); // 网站配置
        $columns = $this->columns->total(['id', 'render', 'page'], [], [], 0, 0); // 栏目
        $urls = []; // 链接
        // 栏目链接处理
        foreach ($columns as &$column){
            if($column['render']) { $urls[] = $column['page']; }
            else { $urls[] = $configs['config_domain_name'].'/'.$column['page'] . '/' .$column['id'].$configs['config_page_suffix']; }
        }
        $messages = $this->messages->total(['id', 'page']); // 信息
        // 信息链接处理
        foreach ($messages as &$message){
            $urls[] = $configs['config_domain_name'] .'/'.$message['page'] . '/' .$message['id'].$configs['config_page_suffix'];
        }
        return $urls;
    }

    /**
     * 缓存html
     *
     * @throws ExceptionsSiteMap
     */
    public function html(): void
    {
        $navigations = $this->columns->navigation(); // 导航
        // 网站配置
        $configs = $this->configMessage->configs(['config_name', 'config_domain_name', 'config_logo', 'config_copyright'], false);
        $copyright = htmlspecialchars_decode($configs['config_copyright']);
        $copyright = str_replace('&nbsp;', ' ', $copyright);
        $html = ''; // html
        $html .= '<html>';
        $html .= '<head>';
        $html .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        $html .= '<title>' . $configs['config_name'] .'</title>';
        $html .= '</head>';
        $html .= '<body>';
        $html .= '<div class="header">';
        $html .= '<div class="title">';
        $html .= '<h1><a href="'.$configs['config_domain_name'].'" alt="'. $configs['config_name'] .'" title="'.$configs['config_name'].'"><img src="'.$configs['config_logo'].'" alt="'.$configs['config_name'].'"/></a></h1>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="center">';
        $html .= '<div class="title">';
        $html .= '<h2>网站地图</h2>';
        $html .= '<span class="more"><a href="'.$configs['config_domain_name'].'" alt="'. $configs['config_name'] .'" title="'.$configs['config_name'].'">首页</a></span>';
        foreach ($navigations as $navigation){
            $html .= '<div class="catalog">';
            $html .= '<h3><a href="'. $navigation['url'] .'">'. $navigation['name'] .'</a></h3>';
            $html .= '<ul class="children">';
            if(count($navigation['children'])){
                foreach ($navigation['children'] as $child){
                    $html .= '<li><a href="'.  $child['url'].'">'. $child['name'] .'</a></li>';
                }
            }
            $html .= '</ul>';
            $html .= '</div>';
        }
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="footer">';
        $html .= '<p class="copyright">';
        $html .= $copyright;
        $html .= '</p>';
        $html .= '</div>';
        $html .= '</body>';
        $html .= '</html>';
        $path = public_path(DIRECTORY_SEPARATOR).$this->html; // 文件路径+文件名
        $file = @fopen($path, 'w+'); // 打开文件，并且删除之前的数据
        if(!$file){ throw new ExceptionsSiteMap($this->html."文件打开失败"); }
        fwrite($file, $html); // 写入内容
        fclose($file); // 关闭文件
    }

    /**
     * 缓存xml
     *
     * @throws ExceptionsSiteMap
     */
    public function xml(): void
    {
        // 网站配置
        $configs = $this->configMessage->configs(['config_xml_priority', 'config_xml_rate'], false);
        $xml = '';
        $xml .= '<?xml version="1.0" encoding="utf-8" ?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        $urls = $this->urls();
        foreach ($urls as &$url){
            $xml .= '<url>';
            $xml .= '<loc>'.$url.'</loc>';
            $xml .= '<lastmod>'.date("Y-m-d", time()).'</lastmod>';
            $xml .= '<changefreq>'.$configs['config_xml_rate'].'</changefreq>';
            $xml .= '<priority>'.$configs['config_xml_priority'].'</priority>';
            $xml .= '</url>';
        }
        $xml .='</urlset>';
        $path = public_path(DIRECTORY_SEPARATOR).$this->xml; // 文件路径+文件名
        $file = @fopen($path, 'w+'); // 打开文件，并且删除之前的数据
        if(!$file){ throw new ExceptionsSiteMap($this->xml."文件打开失败"); }
        fwrite($file, $xml); // 写入内容
        fclose($file); // 关闭文件
    }

    /**
     * 缓存txt
     *
     * @throws ExceptionsSiteMap
     */
    public function txt(): void
    {
        $urls = $this->urls(); // 网站链接
        $txt = implode("\r\n", $urls); // 使用换行把数组转为字符串
        $path = public_path(DIRECTORY_SEPARATOR).$this->txt; // 文件路径+文件名
        $file = @fopen($path, 'w+'); // 打开文件，并且删除之前的数据
        if(!$file){ throw new ExceptionsSiteMap($this->txt."文件打开失败"); }
        fwrite($file, $txt); // 写入内容
        fclose($file); // 关闭文件
    }
}
