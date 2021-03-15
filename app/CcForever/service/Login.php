<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2021/2/22
 */

namespace App\CcForever\service;

use App\Repositories\AdminsRepository;

/**
 * 登陆处理
 *
 * Class Login
 * @package App\CcForever\service
 */
class Login
{
    /**
     * 受权编号
     *
     * @var array
     */
    private $landerIds = [1];

    /**
     * token加密参数
     *
     * @var array
     */
    private $jwtParam = ['id', 'username', 'found', 'lander'];

    /**
     * 编号
     *
     * @return int
     */
    public function tokenId(): int
    {
        $param = $this->tokenParam();
        return count($param) ? (int)$param['id'] : 0;
    }

    /**
     * token中的所有参数
     *
     * @return array
     */
    public function tokenParam(): array
    {
        $key = 'authorization';
        $token = trim(ltrim(lcfirst(app('request')->header($key)), 'bearer'));
        $param = (new Jwt())->check($token);
        return $this->jwtParam !== array_keys($param) ? [] : $param;
    }

    /**
     * 验证路由
     *
     * @param int $adminId
     * @param string $api
     * @throws \App\Exceptions\Login
     */
    public function checkRoute(int $adminId, string $api): void
    {
        // 路由列表
        $routes = (new AdminsRepository())->menus($adminId, ['routes', 'name'], 0);
        // 验证请求接口是否在路由列表中
        if(!in_array($api, $routes)) { throw new \App\Exceptions\Login(); }
    }

    /**
     * 检测受权编号
     *
     * @param int $adminId
     * @return bool
     */
    public function checkLander(int $adminId): bool
    {
        return in_array($adminId, $this->landerIds) ? true : false;
    }
}
