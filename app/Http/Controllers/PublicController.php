<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/7/20
 */
namespace App\Http\Controllers;

use App\CcForever\controller\BaseController;

/**
 * 公共控制器
 *
 * Class PublicController
 * @package App\Http\Controllers
 */
class PublicController extends BaseController
{
    /**
     * 验证码
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function captcha(): \Illuminate\Http\JsonResponse
    {
        $captcha = app('captcha')->create('login', true);
        return $this->resources->success('验证码', ['url' => $captcha['img'], 'key' => $captcha['key']]);
    }
}
