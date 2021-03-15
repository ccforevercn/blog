<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/7/21
 */

namespace App\Http\Controllers\system;

use App\CcForever\{controller\BaseController, traits\Controller as TraitsController};
use App\Exceptions\Menus as ExceptionsMenus;
use App\Http\Requests\Menus\{
    MenusInsertRequest,
    MenusListRequest,
    MenusUpdateRequest,
};
use App\Repositories\MenusRepository;

/**
 * 菜单
 *
 * Class MenusController
 * @package App\Http\Controllers\system
 */
class MenusController extends BaseController
{
    use  TraitsController;

    /**
     * @var MenusRepository
     */
    private $menus;

    public function __construct()
    {
        parent::__construct();
        $this->menus = new MenusRepository();
    }

    /**
     * 列表
     *
     * @param MenusListRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function lst(MenusListRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement lst() method.
        try{
            $where = $request->all(); // 请求参数
            $list = $this->menus->lst($where, [], $where['page'], $where['limit']); // 列表
            return $this->resources->success("列表", $list); // 返回列表
        }catch (ExceptionsMenus $exception) {
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 总数
     *
     * @param MenusListRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function count(MenusListRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement count() method.
        try{
            $where = $request->all(); // 请求参数
            $count = $this->menus->count($where); // 总数
            return $this->resources->success("总数", compact('count')); // 返回总数
        }catch (ExceptionsMenus $exception) {
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 添加
     *
     * @param MenusInsertRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function insert(MenusInsertRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement insert() method.
        try{
            $data = $request->all(); // 添加参数
            $this->menus->insert($data); // 添加
            return $this->resources->notice("添加成功"); // 返回通知
        }catch (ExceptionsMenus $exception) {
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 修改
     *
     * @param MenusUpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(MenusUpdateRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement update() method.
        try{
            $data = $request->all(); // 修改参数
            $this->menus->update($data, $data['id']); // 修改数据
            return $this->resources->notice("修改成功"); // 返回通知
        }catch (ExceptionsMenus $exception){
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
            $this->menus->delete($id);
            return $this->resources->notice("删除成功");
        }catch (ExceptionsMenus $exception) {
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 侧边栏
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sidebar(): \Illuminate\Http\JsonResponse
    {
        try{
            $sidebar = $this->menus->sidebar(); // 获取侧边栏
            return $this->resources->success("侧边栏", $sidebar);
        }catch (ExceptionsMenus $exception) {
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 菜单
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function menus(): \Illuminate\Http\JsonResponse
    {
        try{
            $sidebar = $this->menus->menus(['parent_id', 'name', 'id'], 2); // 获取菜单
            return $this->resources->success("菜单", $sidebar);
        }catch (ExceptionsMenus $exception) {
            return $this->resources->error($exception->getMessage());
        }
    }
}
