<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @date:    2021/2/26
 */

namespace App\Exceptions;

use Throwable;

/**
 * 信息标签异常处理
 *
 * Class MessagesTags
 * @package App\Exceptions
 */
class MessagesTags extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
