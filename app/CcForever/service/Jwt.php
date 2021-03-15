<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2021/2/22
 */

namespace App\CcForever\service;

use \Firebase\JWT\JWT as JwtBase;

/**
 * Token
 *
 * Class JWT
 * @package App\CcForever\service
 */
class Jwt
{
    /**
     * 配置
     *
     * @return array
     */
    private function config(): array
    {
        $config = config('ccforever');
        $secret = !is_null($config) && array_key_exists('jwt_secret', $config) ? $config['jwt_secret'] : '';
        $ttl = !is_null($config) && array_key_exists('jwt_ttl', $config) ? $config['jwt_ttl'] : 0;
        return compact('secret', 'ttl');
    }

    /**
     * 获取Token
     *
     * @param array $payload
     * @return string
     */
    public function get(array $payload): string
    {
        $payload['time'] = time(); // 创建时间
        $token = JwtBase::encode($payload, $this->config()['secret'], 'HS256');
        return $token;
    }

    /**
     * 验证Token并且获取Token中的数据
     *
     * @param string $token
     * @return array
     */
    public function check(string $token): array
    {
        try{
            $config = $this->config();
            // token中的数据
            $data = (array)JwtBase::decode($token, $config['secret'], ['HS256']);
            // 过期时间
            $time = (int)bcadd((string)$data['time'], (string)$config['ttl'], 0);
            // 已过期返回去空数组
            if($time <= time()) return [];
            // 删除限制时间
            unset($data['time']);
            return $data;
        }catch (\Exception $exception){
            return [];
        }
    }
}