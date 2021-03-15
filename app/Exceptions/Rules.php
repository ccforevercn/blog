<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @date:    2021/3/1
 */

namespace App\Exceptions;

use Throwable;

/**
 * 规则异常类
 *
 * Class Rules
 * @package App\Exceptions
 */
class Rules extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
