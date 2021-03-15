<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/7/20
 */

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['prefix'=> '/v1/cms'], function(){ // 后台路由组
    Route::post('/login', 'LoginController@login');// 登陆
    Route::get('/captcha', 'PublicController@captcha');// 验证码
    Route::group(['middleware'=> ['login']], function() {// 需要登陆的api
        Route::namespace('message')->group(function () { // 信息路由组
            // 栏目接口
            Route::get('/columns/list', 'ColumnsController@lst');// 列表
            Route::get('/columns/count', 'ColumnsController@count');// 总数
            Route::post('/columns/insert', 'ColumnsController@insert');// 添加
            Route::put('/columns/update', 'ColumnsController@update');// 修改
            Route::delete('/columns/delete', 'ColumnsController@delete');// 删除
            Route::post('/columns/content', 'ColumnsController@content');// 内容 添加、修改、查询
            Route::get('/columns/columns', 'ColumnsController@columns');// 全部
            // 信息接口
            Route::get('/messages/list', 'MessagesController@lst');// 列表
            Route::get('/messages/count', 'MessagesController@count');// 总数
            Route::post('/messages/insert', 'MessagesController@insert');// 添加
            Route::put('/messages/update', 'MessagesController@update');// 修改
            Route::delete('/messages/delete', 'MessagesController@delete');// 删除
            Route::post('/messages/content', 'MessagesController@content');// 内容 添加、修改、查询
            Route::get('/messages/tags', 'MessagesController@tags');// 标签
            Route::put('/messages/number', 'MessagesController@number');// 点击量、权重、状态修改
            // 标签接口
            Route::get('/tags/list', 'TagsController@lst');// 列表
            Route::get('/tags/count', 'TagsController@count');// 总数
            Route::post('/tags/insert', 'TagsController@insert');// 添加
            Route::put('/tags/update', 'TagsController@update');// 修改
            Route::delete('/tags/delete', 'TagsController@delete');// 删除
            Route::get('/tags/tags', 'TagsController@tags');// 全部
            // 视图接口
            Route::get('/views/list', 'ViewsController@lst');// 列表
            Route::get('/views/count', 'ViewsController@count');// 总数
            Route::post('/views/insert', 'ViewsController@insert');// 添加
            Route::put('/views/update', 'ViewsController@update');// 修改
            Route::delete('/views/delete', 'ViewsController@delete');// 删除
            Route::get('/views/views', 'ViewsController@views');// 类型
        });
        Route::namespace('markets')->group(function () { // 营销路由组
            // 轮播图接口
            Route::get('/banners/list', 'BannersController@lst');// 列表
            Route::get('/banners/count', 'BannersController@count');// 总数
            Route::post('/banners/insert', 'BannersController@insert');// 添加
            Route::put('/banners/update', 'BannersController@update');// 修改
            Route::delete('/banners/delete', 'BannersController@delete');// 删除
        });
        Route::namespace('seo')->group(function () { // seo路由组
            // 友情链接接口
            Route::get('/links/list', 'LinksController@lst');// 列表
            Route::get('/links/count', 'LinksController@count');// 总数
            Route::post('/links/insert', 'LinksController@insert');// 添加
            Route::put('/links/update', 'LinksController@update');// 修改
            Route::delete('/links/delete', 'LinksController@delete');// 删除
            // 缓存接口
            Route::post('/cache/index', 'CacheController@index');// 首页
            Route::post('/cache/search', 'CacheController@search');// 搜索页
            Route::post('/cache/columns', 'CacheController@columns');// 栏目
            Route::post('/cache/message', 'CacheController@message');// 信息
            // robots接口
            Route::get('/robots/content', 'RobotsController@content');// 获取
            Route::put('/robots/update', 'RobotsController@update');// 修改
            // 网站地图接口
            Route::get('/sitemap/index', 'SiteMapController@index');// html缓存
            Route::post('/sitemap/html', 'SiteMapController@html');// html缓存
            Route::post('/sitemap/xml', 'SiteMapController@xml');// xml缓存
            Route::post('/sitemap/txt', 'SiteMapController@txt');// txt缓存
        });
        Route::namespace('config')->group(function () { // 配置路由组
            // 配置分类接口
            Route::get('/config/category/list', 'ConfigCategoryController@lst');// 列表
            Route::get('/config/category/count', 'ConfigCategoryController@count');// 总数
            Route::post('/config/category/insert', 'ConfigCategoryController@insert');// 添加
            Route::put('/config/category/update', 'ConfigCategoryController@update');// 修改
            Route::delete('/config/category/delete', 'ConfigCategoryController@delete');// 删除
            Route::get('/config/category/category', 'ConfigCategoryController@category');// 全部
            // 配置信息接口
            Route::get('/config/message/list', 'ConfigMessageController@lst');// 列表
            Route::get('/config/message/count', 'ConfigMessageController@count');// 总数
            Route::post('/config/message/insert', 'ConfigMessageController@insert');// 添加
            Route::put('/config/message/update', 'ConfigMessageController@update');// 修改
            Route::delete('/config/message/delete', 'ConfigMessageController@delete');// 删除
        });
        Route::namespace('system')->group(function () { // 系统路由组
            // 管理员接口
            Route::get('/admins/list', 'AdminsController@lst');// 列表
            Route::get('/admins/count', 'AdminsController@count');// 总数
            Route::post('/admins/insert', 'AdminsController@insert');// 添加
            Route::put('/admins/update', 'AdminsController@update');// 修改
            Route::delete('/admins/delete', 'AdminsController@delete');// 删除
            // 菜单接口
            Route::get('/menus/list', 'MenusController@lst');// 列表
            Route::get('/menus/count', 'MenusController@count');// 总数
            Route::post('/menus/insert', 'MenusController@insert');// 添加
            Route::put('/menus/update', 'MenusController@update');// 修改
            Route::delete('/menus/delete', 'MenusController@delete');// 删除
            Route::get('/menus/menus', 'MenusController@menus');// 所有
            Route::get('/menus/sidebar', 'MenusController@sidebar');// 侧边栏
            // 规则接口
            Route::get('/rules/list', 'RulesController@lst');// 列表
            Route::get('/rules/count', 'RulesController@count');// 总数
            Route::post('/rules/insert', 'RulesController@insert');// 添加
            Route::put('/rules/update', 'RulesController@update');// 修改
            Route::delete('/rules/delete', 'RulesController@delete');// 删除
            Route::get('/rules/menu', 'RulesController@menu');// 菜单(单个规则的菜单查看)
            Route::get('/rules/menus', 'RulesController@menus');// 所有菜单(当前管理员的所有菜单)
            Route::get('/rules/rules', 'RulesController@rules');// 所有(当前管理员的所有规则)
        });
        Route::namespace('upload')->group(function () { // 上传文件路由组
            // 文件接口
            Route::get('/uploads/list', 'UploadsController@list');// 列表
            Route::get('/uploads/count', 'UploadsController@count');// 总数
            Route::post('/uploads/insert', 'UploadsController@insert');// 上传
            Route::delete('/uploads/delete', 'UploadsController@delete');// 删除
        });
    });
});
