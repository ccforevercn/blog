<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2021/2/22
 */

namespace App\CcForever\service;

/**
 * 请求返回
 *
 * Class Resources
 * @package App\CcForever\service
 */
class Resources
{
    private $success = 200; // 成功状态码

    private $notice = 206; // 通知状态码

    private $error = 400; // 失败状态码

    private $login = 401; // 登陆状态码

    /**
     * 成功返回
     *
     * @param string $message
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function success(string $message, array $data = []): \Illuminate\Http\JsonResponse
    {
        return self::response($message, $data, $this->success);
    }

    /**
     * 通知返回
     *
     * @param string $message
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function notice(string $message, array $data = []): \Illuminate\Http\JsonResponse
    {
        return self::response($message, $data, $this->notice);
    }

    /**
     * 失败返回
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function error(string $message): \Illuminate\Http\JsonResponse
    {
        return self::response($message, [], $this->error);
    }

    /**
     * 暂无权限返回
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function unauthorized(): \Illuminate\Http\JsonResponse
    {
        return $this->error("暂无权限操作");
    }

    /**
     * 登陆返回
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(string $message): \Illuminate\Http\JsonResponse
    {
        return self::response($message, [], $this->login);
    }

    /**
     * 格式化返回值
     *
     * @param string $message
     * @param array $data
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    private function response(string $message, array $data, int $code): \Illuminate\Http\JsonResponse
    {
        return response()->json(['message' => $message, 'data' => $data, 'code'=> $code]);
    }
}