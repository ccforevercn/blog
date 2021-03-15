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
 * 规则菜单异常处理
 *
 * Class RulesMenus
 * @package App\Exceptions
 */
class RulesMenus extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
