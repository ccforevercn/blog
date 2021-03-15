<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/7/20
 */

namespace App\Http\Controllers;

use App\CcForever\controller\BaseController;
use App\Exceptions\Admins as ExceptionsAdmins;
use App\Http\Requests\LoginRequest;
use App\Repositories\AdminsRepository;

/**
 * 登陆控制器
 *
 * Class LoginController
 * @package App\Http\Controllers
 */
class LoginController extends BaseController
{
    /**
     * @var AdminsRepository
     */
    private $admin;

    public function __construct()
    {
        parent::__construct();
        $this->admin = new AdminsRepository();
    }

    /**
     * 登陆
     *
     * @param LoginRequest $loginRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $loginRequest): \Illuminate\Http\JsonResponse
    {
        // 登陆请求数据
        $login = $loginRequest->all();
        // 验证码验证
        if(!captcha_api_check($login['captcha'], $login['key'])){ return $this->resources->error('验证码错误'); }
        // 验证管理员账号密码
        try{
            // 登陆验证
            $adminInfo = $this->admin->login($login['username'], $login['password']);
            // 返回登陆信息
            return $this->resources->success("登陆成功", $adminInfo);
        }catch (ExceptionsAdmins $exception) {
            // 登陆失败
            return $this->resources->error($exception->getMessage());
        }
    }
}
