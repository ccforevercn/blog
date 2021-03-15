<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/7/20
 */

namespace App\CcForever\controller;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as Controller;
use App\CcForever\service\Resources;

/**
 * Controller
 *
 * Class BaseController
 * @package App\CcForever\controller
 */
class BaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 请求返回
     *
     * @var Resources
     */
    protected $resources;

    public function __construct()
    {
        date_default_timezone_set("Asia/Shanghai"); // 设置时区
        $this->resources = new Resources();
    }
}
