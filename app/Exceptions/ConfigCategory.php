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
 * 配置分类异常处理
 *
 * Class ConfigCategory
 * @package App\Exceptions
 */
class ConfigCategory extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
