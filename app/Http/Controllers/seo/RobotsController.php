<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/9/3
 */
namespace App\Http\Controllers\seo;

use App\CcForever\{controller\BaseController, service\Robots};
use App\Exceptions\Robots as ExceptionsRobots;

/**
 * Robots
 *
 * Class RobotsController
 * @package App\Http\Controllers\seo
 */
class RobotsController extends BaseController
{
    /**
     * @var Robots
     */
    private $robots;

    public function __construct()
    {
        parent::__construct();
        $this->robots = new Robots();
    }

    /**
     * 内容
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function content(): \Illuminate\Http\JsonResponse
    {
        $content = $this->robots->content(); // 内容获取
        return $this->resources->success("内容", compact('content'));
    }

    /**
     * 修改
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(): \Illuminate\Http\JsonResponse
    {
        try {
            $content = (string)app('request')->input('content', '');
            $this->robots->update($content);
            return $this->resources->notice("修改成功");
        }catch (ExceptionsRobots $exception) {
            return $this->resources->error($exception->getMessage());
        }
    }
}
