<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/7/21
 */

namespace App\Repositories;


use App\Exceptions\{
    Database, Menus as ExceptionsMenus
};
use App\Models\Menus;
use App\CcForever\{
    interfaces\Repository as InterfacesRepository,
    service\Check,
    service\HandleArray,
    service\Login,
    service\model\Menus as ServiceMenus,
    traits\Repository as TraitsRepository
};

/**
 * 菜单
 *
 * Class MenusRepository
 * @package App\Repositories
 */
class MenusRepository implements InterfacesRepository
{
    use TraitsRepository;

    /**
     * 管理员服务类
     *
     * @var ServiceMenus
     */
    private $menus;

    /**
     * 验证类
     *
     * @var Check
     */
    private $check;

    /**
     * 数组处理类
     *
     * @var HandleArray
     */
    private $array;

    /**
     * 登陆处理类
     *
     * @var Login
     */
    protected $login;

    public function __construct()
    {
        $this->model = new Menus();
        $this->check = new Check();
        $this->array = new HandleArray();
        $this->menus = new ServiceMenus();
        $this->login = new Login();
    }

    /**
     * 列表
     *
     * @param array $where
     * @param array $order
     * @param int $page
     * @param int $limit
     * @return array
     * @throws ExceptionsMenus
     */
    public function lst(array $where, array $order, int $page, int $limit): array
    {
        // TODO: Implement lst() method.
        try{
            $offset = $this->check->page($page, $limit); // 分页起始值
            $wheres = $this->array->getNotNull($where, 'int', ['parent_id'], ['menu']);
            return $this->model->lst($wheres, $order, $offset, $limit); // 查询并返回
        }catch (Database $exception){
            throw new ExceptionsMenus($exception->getMessage());
        }
    }

    /**
     * 总数
     *
     * @param array $where
     * @return int
     * @throws ExceptionsMenus
     */
    public function count(array $where): int
    {
        // TODO: Implement count() method.
        try{
            $wheres = $this->array->getNotNull($where, 'int', ['parent_id'], ['menu']);
            return $this->model->count($wheres); // 查询并返回
        }catch (Database $exception){
            throw new ExceptionsMenus($exception->getMessage());
        }
    }

    /**
     * 添加
     *
     * @param array $data
     * @throws ExceptionsMenus
     */
    public function insert(array $data): void
    {
        // TODO: Implement insert() method.
        // 重置插入数据
        $insert = $this->param($data, true, 0);
        try {
            $insert['is_del'] = 0;
            $insert['add_time'] = time();
            $this->model->create($insert); // 添加
        }catch (Database $exception) {
            throw new ExceptionsMenus($exception->getMessage());
        }
    }

    /**
     * 重置参数
     *
     * @param array $data
     * @param bool $type
     * @param int $id
     * @return array
     * @throws ExceptionsMenus
     */
    private function param(array $data, bool $type, int $id): array
    {
        // 重置参数
        $param = $this->array->getNotNull($data, 'string', ['name'], ['parent_id', 'int'], ['routes'], ['page'], ['icon'], ['sort', 'int'], ['menu', 'int']);
        // 验证必要参数是否存在
        $checkKey = $this->array->isKey($param, 'name', 'parent_id', 'menu');
        if(!$checkKey){ throw new ExceptionsMenus("请填写必填参数值"); }
        switch ($param['menu']) {
            case 1:
                $checkPage = $this->array->isKey($param, 'page');
                if(!$checkPage){ throw new ExceptionsMenus("请填写页面链接"); }
                break;
            default:
                $checkRoutes = $this->array->isKey($param, 'routes');
                if(!$checkRoutes){ throw new ExceptionsMenus("请填写路由地址"); }
                if($type) {
                    // 验证路由
                    try {
                        $checkRoutes = $this->model->count(['routes' => $param['routes']]);
                        if($checkRoutes) { throw new ExceptionsMenus("路由地址已存在"); }
                    }catch (Database $exception) {
                        throw new ExceptionsMenus($exception->getMessage());
                    }
                }else {
                    // 验证路由
                    $checkSqlRoutes = $this->model->get($this->model->table, ['id', 'routes'], [], ['routes' => $param['routes']], [], ['offset' => 0, 'limit' => 2]);
                    switch (count($checkSqlRoutes)) {
                        case 0:break;
                        case 1:if($checkSqlRoutes[0]['id'] !== $id) { throw new ExceptionsMenus("路由已存在，不能重复添加"); } break;
                        default: throw new ExceptionsMenus("路由已存在，不能重复添加");
                    }
                }
        }
        return $param;
    }

    /**
     * 修改
     *
     * @param array $data
     * @param int $id
     * @throws ExceptionsMenus
     */
    public function update(array $data, int $id): void
    {
        // TODO: Implement update() method.
        // 验证编号
        $checkId = $this->model->checkId($this->model->table, $id);
        if(!$checkId) { throw new ExceptionsMenus("参数错误"); }
        // 重置修改数据
        $update = $this->param($data, false, $id);
        // 验证修改数据和数据库数据是否一致
        $checkUpdate = $this->model->first($this->model->table, $id, array_keys($update)) == $update;
        if($checkUpdate) { return; }
        try {
            $this->model->update($update, $id); // 修改数据
        }catch (Database $exception) {
            throw new ExceptionsMenus($exception->getMessage());
        }
    }

    /**
     * 删除
     *
     * @param int $id
     * @throws ExceptionsMenus
     */
    public function delete(int $id): void
    {
        // TODO: Implement delete() method.
        // 验证编号
        $checkId = $this->model->checkId($this->model->table, $id);
        if(!$checkId) { throw new ExceptionsMenus("参数错误"); }
        try{
            // 验证是否有下级栏目
            $list = $this->model->lst(['parent_id' => $id], [], 0, 1);
            if(count($list)) { throw new ExceptionsMenus("请先删除子菜单"); }
            $this->model->update(['is_del' => 1], $id);
        }catch (Database $exception) {
            throw new ExceptionsMenus($exception->getMessage());
        }
    }

    /**
     * 侧边栏
     *
     * @return array
     * @throws ExceptionsMenus
     */
    public function sidebar(): array
    {
        $sidebar = $this->menus(['parent_id', 'page', 'name', 'icon', 'id'], 1);
        return (new ServiceMenus())->sidebar($sidebar, 0); // 格式化侧边栏并返回
    }

    /**
     * 菜单
     *
     * @param array $selects
     * @param int $menu
     * @return array
     * @throws ExceptionsMenus
     */
    public function menus(array $selects, int $menu): array
    {
        $adminId = $this->login->tokenId(); // 登陆编号
        // 受权侧边栏
        $where = [];
        if($menu == 1 || $menu == 0) { $where = ['menu' => $menu]; }
        if($this->login->checkLander($adminId)) { $sidebar = $this->model->get($this->model->table, $selects, [], $where, ['sort' => 'DESC']); }
        // 授权侧边栏
        else { $sidebar = (new AdminsRepository())->menus($adminId, $selects, $menu); }
        if(!count($sidebar)) { throw new ExceptionsMenus("暂无菜单"); }
        return $sidebar;
    }

    /**
     * 验证编号(批量)
     *
     * @param array $ids
     * @return array
     */
    public function checkIds(array $ids): array
    {
        try {
            $menus = $this->menus(['id', 'name', 'parent_id'], 2); // 菜单信息
            // 菜单编号
            $menuIds = array_map(function ($item){ return $item['id']; }, $menus);
            // $menuIds和$ids交集后的总数
            $checkIds = count(array_intersect($menuIds, $ids)) === count($ids);
            // 编号不存在
            if(!$checkIds){ return []; }
            foreach ($ids as $id){ $ids = $this->menus->addMenusParentIds($menus, $id, $ids); }
            return $ids;
        }catch (ExceptionsMenus $exception) {
            return [];
        }
    }

    /**
     * 菜单信息
     *
     * @param array $ids
     * @return array
     */
    public function menuIdsInfo(array $ids): array
    {
        return $this->model->get($this->model->table, ['id', 'name', 'parent_id'], [], ['id' => $ids]);
    }
}
