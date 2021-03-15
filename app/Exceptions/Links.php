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
 * 友情链接异常处理
 *
 * Class Links
 * @package App\Exceptions
 */
class Links extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
