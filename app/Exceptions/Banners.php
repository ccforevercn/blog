<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @date:    2021/2/27
 */

namespace App\Exceptions;

use Throwable;

/**
 * 轮播图异常处理
 *
 * Class Banners
 * @package App\Exceptions
 */
class Banners extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
