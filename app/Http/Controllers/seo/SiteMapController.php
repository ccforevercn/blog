<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/9/3
 */
namespace App\Http\Controllers\seo;

use App\CcForever\{controller\BaseController, service\SiteMap};
use App\Exceptions\SiteMap as ExceptionsSiteMap;

/**
 * 网站地图
 *
 * Class SiteMapController
 * @package App\Http\Controllers\seo
 */
class SiteMapController extends BaseController
{
    /**
     * @var SiteMap
     */
    private $siteMap;

    public function __construct()
    {
        parent::__construct();
        $this->siteMap = new SiteMap();
    }

    /**
     * 链接
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $urls = $this->siteMap->urls();
        return $this->resources->success("链接", $urls);
    }

    /**
     * html
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function html(): \Illuminate\Http\JsonResponse
    {
        try {
            $this->siteMap->html();
            return $this->resources->notice("缓存成功");
        }catch (ExceptionsSiteMap $exception) {
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * xml
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function xml(): \Illuminate\Http\JsonResponse
    {
        try {
            $this->siteMap->xml();
            return $this->resources->notice("缓存成功");
        }catch (ExceptionsSiteMap $exception) {
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * txt
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function txt(): \Illuminate\Http\JsonResponse
    {
        try {
            $this->siteMap->txt();
            return $this->resources->notice("缓存成功");
        }catch (ExceptionsSiteMap $exception) {
            return $this->resources->error($exception->getMessage());
        }
    }
}
