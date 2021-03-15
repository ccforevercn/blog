<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2021/2/24
 */

namespace App\Exceptions;

use Throwable;

/**
 * 数据库异常处理
 *
 * Class Database
 * @package App\Exceptions
 */
class Database extends \Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}