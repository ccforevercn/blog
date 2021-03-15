<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @date:    2021/2/27
 */

namespace App\CcForever\service;

use App\Exceptions\Robots as ExceptionsRobots;
/**
 * Robots
 *
 * Class Robots
 * @package App\CcForever\service
 */
class Robots
{
    /**
     * 文件名称
     *
     * @var string
     */
    private $fileName = 'robots.txt';

    /**
     * 内容
     *
     * @return string
     */
    public function content(): string
    {
        $content = ''; // 文件内容
        // 文件路径+文件名
        $path = public_path(DIRECTORY_SEPARATOR).$this->fileName;
        $file = @fopen($path, 'r'); // 只读方式打开文件
        if(!$file) return $content; // 打开失败返回空串
        $content = fread($file, filesize($path)); // 读取文件内容
        fclose($file); // 关闭文件
        return $content;
    }


    /**
     * 修改
     *
     * @param string $content
     * @throws ExceptionsRobots
     */
    public function update(string $content): void
    {
        // 验证内容是否为空
        if(!strlen($content)) { throw new ExceptionsRobots("内容不能为空"); }
        // 格式化字符类型
        $content = str_replace("\r\n", "\n", $content);
        $content = str_replace("\n", "\r\n", $content);
        // 设置编号为urf-8
        $content = (new Util())->encodeChange($content, 'utf-8');
        // 切割为数组
        $contents = preg_split('/(?<!^)(?!$)/u', $content);
        // 验证是否有非法字符
        foreach ($contents as &$value){
            $ascii = ord($value); // 获取每个字符串的ascii值
            switch ($ascii){
                case $ascii > 127:
                    throw new ExceptionsRobots("内容只能填写字母及数字");
                default:;
            }
        }
        // 文件路径+文件名
        $path = public_path(DIRECTORY_SEPARATOR).$this->fileName;
        // 打开文件，并且删除之前的数据
        $file = @fopen($path, 'w+');
        if(!$file) { throw new ExceptionsRobots("修改失败"); }
        fwrite($file, $content); // 写入内容
        fclose($file); // 关闭文件
    }
}
