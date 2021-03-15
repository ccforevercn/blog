<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/7/22
 */

namespace App\Http\Controllers\system;

use App\CcForever\{
    controller\BaseController,
    traits\Controller as TraitsController
};
use App\Exceptions\Admins as ExceptionsAdmins;
use App\Http\Requests\Admins\{
    AdminsInsertRequest,
    AdminsListRequest,
    AdminsUpdateRequest,
};
use App\Repositories\AdminsRepository;

/**
 * 管理员
 *
 * Class AdminsController
 * @package App\Http\Controllers\system
 */
class AdminsController extends BaseController
{
    use TraitsController;

    /**
     * @var AdminsRepository
     */
    private $admins;

    public function __construct()
    {
        parent::__construct();
        $this->admins = new AdminsRepository();
    }

    /**
     * 列表
     *
     * @param AdminsListRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function lst(AdminsListRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement lst() method.
        try{
            $where = $request->all(); // 请求参数
            $list = $this->admins->lst($where, [], $where['page'], $where['limit']); // 列表
            return $this->resources->success("列表", $list); // 返回列表
        }catch (ExceptionsAdmins $exception) {
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 总数
     *
     * @param AdminsListRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function count(AdminsListRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement count() method.
        try{
            $where = $request->all(); // 请求参数
            $count = $this->admins->count($where); // 总数
            return $this->resources->success("总数", compact('count')); // 返回总数
        }catch (ExceptionsAdmins $exception) {
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 添加
     *
     * @param AdminsInsertRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function insert(AdminsInsertRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement insert() method.
        try{
            $data = $request->all(); // 添加参数
            $this->admins->insert($data); // 添加
            return $this->resources->notice("添加成功"); // 返回通知
        }catch (ExceptionsAdmins $exception) {
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 修改
     *
     * @param AdminsUpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(AdminsUpdateRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement update() method.
        try{
            $data = $request->all(); // 修改参数
            $this->admins->update($data, $data['id']); // 修改数据
            return $this->resources->notice("修改成功"); // 返回通知
        }catch (ExceptionsAdmins $exception){
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
        try{
            $id = (int)app('request')->input('id', 0);
            $this->admins->delete($id);
            return $this->resources->notice("删除成功");
        }catch (ExceptionsAdmins $exception) {
            return $this->resources->error($exception->getMessage());
        }
    }
}
