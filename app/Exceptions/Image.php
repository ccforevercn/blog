<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @date:    2021/3/2
 */

namespace App\Exceptions;

use Throwable;

/**
 * 图片上传异常处理
 *
 * Class Image
 * @package App\Exceptions
 */
class Image extends \Exception
{
    /**
     * 上传信息
     *
     * @var array
     */
    private $uploads;

    public function __construct($message = "", $code = 0, $uploads = [], Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->uploads = $uploads;
    }

    /**
     * 获取上传信息
     *
     * @return array
     */
    public function getUploads(): array
    {
        return  $this->uploads;
    }
}
