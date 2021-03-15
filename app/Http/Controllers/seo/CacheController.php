<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/8/29
 */

namespace App\Http\Controllers\seo;

use App\CcForever\{ controller\BaseController, service\Caches };
use App\Exceptions\Caches as ExceptionsCaches;

/**
 * 缓存
 *
 * Class CacheController
 * @package App\Http\Controllers\seo
 */
class CacheController extends BaseController
{
    /**
     * @var Caches
     */
    private $caches;

    public function __construct()
    {
        parent::__construct();
        $this->caches = new Caches();
    }

    /**
     * 首页
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        try {
            $path = $this->caches->index();
            return $this->resources->notice("缓存成功", $path);
        }catch (ExceptionsCaches $exception) {
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 栏目
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function columns(): \Illuminate\Http\JsonResponse
    {
        try {
            $id = (int)app('request')->input('id', 0); // 栏目编号
            $path = $this->caches->columns(abs($id));
            return $this->resources->notice("缓存成功", $path);
        }catch (ExceptionsCaches $exception) {
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 信息
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function message(): \Illuminate\Http\JsonResponse
    {
        try {
            $id = (int)app('request')->input('id', 0); // 信息编号
            $path = $this->caches->messages(abs($id));
            return $this->resources->notice("缓存成功", $path);
        }catch (ExceptionsCaches $exception) {
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 搜索页
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(): \Illuminate\Http\JsonResponse
    {
        try {
            $path = $this->caches->search();
            return $this->resources->notice("缓存成功", $path);
        }catch (ExceptionsCaches $exception) {
            return $this->resources->error($exception->getMessage());
        }
    }
}
