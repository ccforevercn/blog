<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/8/5
 */

namespace App\Http\Controllers\markets;

use App\Exceptions\Banners as ExceptionsBanners;
use App\CcForever\{
    controller\BaseController,
    traits\Controller as TraitsController
};
use App\Http\Requests\Banners\{
    BannersInsertRequest,
    BannersListRequest,
    BannersUpdateRequest
};
use App\Repositories\BannersRepository;

/**
 * 轮播图
 *
 * Class BannersController
 * @package App\Http\Controllers\markets
 */
class BannersController extends BaseController
{
    use TraitsController;

    /**
     * @var BannersRepository
     */
    private $banners;

    public function __construct()
    {
        parent::__construct();
        $this->banners = new BannersRepository();
    }

    /**
     * 列表
     *
     * @param BannersListRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function lst(BannersListRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement lst() method.
        try {
            $where = $request->all(); // 条件
            $list = $this->banners->lst($where, [], $where['page'], $where['limit']); // 获取列表
            return $this->resources->success("列表", $list); // 返回列表
        }catch (ExceptionsBanners $exception) {
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 总数
     *
     * @param BannersListRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function count(BannersListRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement count() method.
        try {
            $where = $request->all(); // 条件
            $count = $this->banners->count($where); // 获取总数
            return $this->resources->success("总数", compact('count')); // 返回总数
        }catch (ExceptionsBanners $exception) {
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 添加
     *
     * @param BannersInsertRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function insert(BannersInsertRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement insert() method.
        try {
            $data = $request->all(); // 参数
            $this->banners->insert($data); // 添加
            return $this->resources->notice("添加成功"); // 返回
        }catch (ExceptionsBanners $exception) {
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 修改
     *
     * @param BannersUpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(BannersUpdateRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement update() method.
        try {
            $data = $request->all(); // 参数
            $this->banners->update($data, $data['id']); // 修改
            return $this->resources->notice("修改成功"); // 返回
        }catch (ExceptionsBanners $exception) {
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 删除
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement delete() method.
        try {
            $id = (int)app('request')->input('id', 0);
            $this->banners->delete($id); // 删除
            return $this->resources->notice("删除成功"); // 返回
        }catch (ExceptionsBanners $exception) {
            return $this->resources->error($exception->getMessage());
        }
    }
}
