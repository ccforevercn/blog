<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2021/2/22
 */

namespace App\CcForever\service;

/**
 * 工具类
 *
 * Class Util
 * @package App\CcForever\service
 */
class Util
{

    /**
     * 表前缀
     *
     * @return string
     */
    public function tablePrefix(): string
    {
        // 数据库配置
        $databases = config('database');
        // 表前缀
        $prefix  = !is_null($databases) && array_key_exists("db_prefix", $databases) ? $databases['db_prefix'] : "cc_";
        // 返回前缀
        return $prefix;
    }

    /**
     * 表备注
     *
     * @param string $table
     * @param string $comment
     */
    public function setTableComment(string $table, string $comment): void
    {
        // 表前缀
        $prefix  = $this->tablePrefix();
        // 表注释SQL
        $sql = "ALTER TABLE `". $prefix . $table ."` COMMENT '".$comment. "'";
        // 执行SQL
        \Illuminate\Support\Facades\DB::statement($sql);
    }

    /**
     * 编码转换
     *
     * @param string $string
     * @param string $encode
     * @return string
     */
    public function encodeChange(string $string, string $encode): string
    {
        $encodeArr = ['ASCII','UTF-8','GB2312','GBK','BIG5']; // 编码格式
        if(!in_array(strtoupper($encode), $encodeArr)){ return $string; } // 编码格式错误
        $encodeString = mb_detect_encoding($string, $encodeArr);  // 获取$string的编码格式
        return mb_convert_encoding($string, $encode, $encodeString); //  转码后的字符串
    }

    /**
     * 错误码对应的提示信息获取
     *
     * @param int $code
     * @return string
     */
    public function exceptionsMessage(int $code): string
    {
        switch ($code){
            case $code < 300 && $code >= 200:
                $errorMessage = config('illegal.error_message_success');
                break;
            case $code < 400 && $code >= 300:
                $errorMessage = config('illegal.error_message_redirect');
                break;
            case $code < 500 && $code >= 400:
                $errorMessage = config('illegal.error_message_error');
                break;
            case $code < 600 && $code >= 500:
                $errorMessage = config('illegal.error_message_inside_error');
                break;
            default:
                $errorMessage = config('illegal.error_message_default');
        }
        return $errorMessage;
    }

    /**
     * 字符串截取
     *
     * @param string $string
     * @param int $start
     * @param int $length
     * @param bool $ellipsis
     * @param string $ending
     * @return string
     */
    public function cutString(string $string, int $start, int $length, bool $ellipsis, string $ending): string
    {
        if(!strlen($string)) { return $string; } // 验证字符串是否为空
        $string = htmlspecialchars_decode($string); // 将特殊的HTML实体转换回普通字符
        $string = $this->manageStyle($string); // 处理样式(html转text处理html中的标签)
        // 验证是否追加$ending字符 类似  ...
        if($ellipsis){
            // 验证截取字符串的长度是大于截取长度赋值$ending字符，反之把$ending清空
            $ending = mb_strlen($string) > bcsub($length, $start, 0) ? $ending : '';
            // 截取字符串并追加$ending字符返回
            return mb_substr($string, $start, $length).$ending;
        }
        // 截取字符串并返回
        return mb_substr($string, $start, $length);
    }

    /**
     * 处理样式(html转text处理html中的标签)
     *
     * @param string $string
     * @return string
     */
    private function manageStyle(string $string): string
    {
        $string = preg_replace("/<sty(.*)\\/style>|<scr(.*)\\/script>|<!--(.*)-->/isU", "", $string);
        $text = "";
        $start = 1;
        for($i = 0; $i < strlen($string); $i++)
        {
            if($start == 0 && $string[$i] ==">"){ $start = 1; }
            else if($start == 1){
                if($string[$i] == "<"){ $start = 0; $text .= " "; }
                else if(ord($string[$i]) > 31){ $text .= $string[$i]; }
            }
        }
        $text = str_replace("　"," ",$text);
        $text = preg_replace("/&([^;&]*)(;|&)/","",$text);
        $text = preg_replace("/[ ]+/s"," ",$text);
        return $text;
    }

    /**
     * 地址
     *
     * @param array $result
     * @param array $configs
     * @return string
     */
    public function url(array $result, array $configs): string
    {
        $urlSeparator = '/'; // 域名分隔符
        $checkRender = array_key_exists('render', $result) && $result['render']; // 验证地址类型
        if($checkRender) { $url = $result['page']; } // 外链
        else { $url = $urlSeparator.$result['page'].$urlSeparator.$result['id'].$configs['config_page_suffix']; } // 内链
        return $url;
    }
}
