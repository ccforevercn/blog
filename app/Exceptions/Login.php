<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2021/2/22
 */

namespace App\Exceptions;

/**
 * 登陆处理异常
 *
 * Class Login
 * @package App\Exceptions
 */
class Login extends \Exception
{

    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}