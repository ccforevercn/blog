<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/7/27
 */

return [
    'jwt_secret' => env('JWT_SECRET', md5('ccforever.cn')), // jwt秘钥
    'jwt_ttl' => env('JWT_TTL', 7200),  // jwt有效时间
];
