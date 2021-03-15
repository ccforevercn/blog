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
 * 缓存异常处理
 *
 * Class Caches
 * @package App\Exceptions
 */
class Caches extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
