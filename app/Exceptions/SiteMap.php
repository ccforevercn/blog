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
 * 网站地图异常处理
 *
 * Class SiteMap
 * @package App\Exceptions
 */
class SiteMap extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
