<?php

namespace App\Exceptions;

use Throwable;

/**
 * 留言异常处理
 *
 * Class Board
 * @package App\Exceptions
 */
class Board extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
