<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/7/20
 */

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// 首页
Route::get('/', function () {
    return '<div style="text-align: center;font-size: 26px;margin-top: 300px;color: #000000;font-weight: 800;"><a href="http://ccforever.com" target="_blank" style="color: #000000;text-decoration: none;">ccforever</a>-技术博客!</div>';
});
