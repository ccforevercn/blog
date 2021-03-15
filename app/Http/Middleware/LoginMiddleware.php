<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/7/20
 */
namespace App\Http\Middleware;

use App\CcForever\service\{
    Login, Resources
};
use App\Exceptions\Login as ExceptionsLogin;
use Closure;

/**
 * 登陆
 *
 * Class LoginMiddleware
 * @package App\Http\Middleware
 */
class LoginMiddleware
{
    /**
     * 授权账号类型
     *
     * @var string
     */
    private $lander = 'admin';

    /**
     * 未授权路由
     *
     * @var array
     */
    private $noRoute = ['/menus/sidebar', '/menus/menus', '/logout', '/admins/message'];

    /**
     * 登陆处理
     *
     * @var Login
     */
    private $login;

    /**
     * 请求返回
     * @var Resources
     */
    private $resources;

    public function __construct(Login $login, Resources $resources)
    {
        $this->login = $login;
        $this->resources = $resources;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 获取token参数
        $param = $this->login->tokenParam();
        // 参数不存在重新登陆
        if(!count($param)){ return $this->resources->login("请先登陆"); }
        // 没有权限访问
        if($param['lander'] !== $this->lander) { return $this->resources->unauthorized(); }
        // 获取当前请求的路由
        $path = app('request')->path();
        // 获取路由前缀
        $route = app('request')->route()->getPrefix();
        // 接口对应菜单表的url字段
        $api = str_replace($route, '', $path);
        // 检测当前接口是否需要授权
        if(!in_array($api, $this->noRoute) && !$this->login->checkLander($param['id'])) {
            try{
                // 检测是否有权限访问接口
                $this->login->checkRoute($param['id'], $api);
            }catch (ExceptionsLogin $exception) {
                return $this->resources->unauthorized();
            }
        }
        return $next($request);
    }
}
