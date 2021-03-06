<?php

namespace App\Exceptions;

use App\CcForever\service\{Resources, Util};
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\Debug\Exception\FlattenException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * 处理访问失败
     *
     * @param \Illuminate\Http\Request $request
     * @param Exception $exception
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $exception)
    {
        $errorObject = FlattenException::create($exception);
        $code = $errorObject->getStatusCode(); // 获取状态码
        $errorMessage = ''; // 提示信息
        $debug = config('app.debug', false); // 获取是否开启debug
        // 开启debug时，获取错误信息
        if($debug){ $errorMessage = $errorObject->getMessage(); }
        // 提示信息为空时获取自定义提示
        if(!strlen($errorMessage)){ $errorMessage = (new Util)->exceptionsMessage($code); }
        return (new Resources)->error($errorMessage);
//        return parent::render($request, $exception);
    }
}
