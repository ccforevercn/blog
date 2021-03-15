<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2021/2/25
 */

namespace App\Exceptions;


use Throwable;

/**
 * 栏目异常处理
 *
 * Class Columns
 * @package App\Exceptions
 */
class Columns extends \Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}