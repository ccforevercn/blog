<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @date:    2021/2/26
 */

namespace App\CcForever\service;

/**
 * 时间类
 *
 * Class Time
 * @package App\CcForever\service
 */
class Time
{

    /**
     * 毫秒
     *
     * @return int
     */
    public function millisecond(): int
    {
        // 获取毫秒(单位是秒)$milli 秒$second
        list($milli, $second) = explode(' ', microtime());
        // 获取格式化后的毫秒并返回
        return (int)sprintf('%.0f', (floatval($milli) + floatval($second)) * 1000);
    }

    /**
     * 时间路径
     *
     * @param string $path
     * @param int $type
     * @param string $ds
     * @return string
     */
    public function path(string $path, int $type = 2, string $ds = '/')
    {
        $path =  $ds.ltrim(rtrim($path)); // 获取完整路径
        switch ($type){
            case 1: // 按年创建文件夹
                $path .= $ds.date('Y');
                break;
            case 2: // 按年月创建文件夹
                $path .=  $ds.date('Y').$ds.date('m');
                break;
            case 3: // 按年月日创建文件夹
                $path .=  $ds.date('Y').$ds.date('m').$ds.date('d');
                break;
        }
        return $path.$ds;
    }
}
