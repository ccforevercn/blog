<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/7/23
 */

namespace App\Repositories;

use App\Exceptions\{
    Database, Rules as ExceptionsRules, RulesMenus as ExceptionsRulesMenus, Menus as ExceptionsMenus
};
use App\CcForever\{interfaces\Repository as InterfacesRepository,
    service\Check,
    service\HandleArray,
    service\Login,
    service\Time,
    service\model\Menus as ServiceMenus,
    traits\Repository as TraitsRepository};
use App\Models\Rules;

/**
 * 规则
 *
 * Class RulesRepository
 * @package App\Repositories
 */
class RulesRepository implements InterfacesRepository
{
    use TraitsRepository;

    /**
     * 登陆处理类
     *
     * @var Login
     */
    private $login;

    /**
     * @var Check
     */
    private $check;

    /**
     * @var HandleArray
     */
    private $array;

    /**
     * @var MenusRepository
     */
    private $menus;

    public function __construct()
    {
        $this->model = new Rules();
        $this->check = new Check();
        $this->login = new Login();
        $this->array = new HandleArray();
        $this->menus = new MenusRepository();
    }

    /**
     * 列表
     *
     * @param array $where
     * @param array $order
     * @param int $page
     * @param int $limit
     * @return array
     * @throws ExceptionsRules
     */
    public function lst(array $where, array $order, int $page, int $limit): array
    {
        // TODO: Implement lst() method.
        try{
            // 验证父级编号，如果父级编号不存在则返回所有权限下的管理员
            $adminIds = (new AdminsRepository())->subordinateIds($this->login->tokenId()); // 获取当前管理员编号和下级管理员编号
            // 没有下级
            if(!count($adminIds)) { throw new ExceptionsRules("暂无列表"); }
            $offset = $this->check->page($page, $limit); // 分页起始值
            $wheres = $this->array->getNotNull($where, 'int', ['admin_id']);
            // 管理员编号不存在时
            if(!$this->array->isKey($wheres, 'admin_id')) { $wheres['admin_id'] = $adminIds; }
            return $this->model->lst($wheres, $order, $offset, $limit); // 查询并返回
        }catch (Database $exception){
            throw new ExceptionsRules($exception->getMessage());
        }
    }

    /**
     * 总数
     *
     * @param array $where
     * @return int
     * @throws ExceptionsRules
     */
    public function count(array $where): int
    {
        // TODO: Implement count() method.
        try{
            // 验证父级编号，如果父级编号不存在则返回所有权限下的管理员
            $adminIds = (new AdminsRepository())->subordinateIds($this->login->tokenId()); // 获取当前管理员编号和下级管理员编号
            // 没有下级
            if(!count($adminIds)) { throw new ExceptionsRules("暂无列表"); }
            $wheres = $this->array->getNotNull($where, 'int', ['admin_id']);
            // 管理员编号不存在时
            if(!$this->array->isKey($wheres, 'admin_id')) { $wheres['admin_id'] = $adminIds; }
            return $this->model->count($wheres); // 查询并返回
        }catch (Database $exception){
            throw new ExceptionsRules($exception->getMessage());
        }
    }

    /**
     * 参数获取(添加、删除)
     *
     * @param array $param
     * @return array
     * @throws ExceptionsRules
     */
    private function param(array $param): array
    {
        // 验证父级编号，如果父级编号不存在则返回所有权限下的管理员
        $adminIds = (new AdminsRepository())->subordinateIds($this->login->tokenId()); // 获取当前管理员编号和下级管理员编号
        // 没有下级
        if(!count($adminIds)) { throw new ExceptionsRules("暂无修改权限"); }
        // 重置数据
        $format = $this->array->getNotNull($param, 'string', ['menus_id'], ['name']);
        // 验证必要参数菜单编号、名称是否为空
        $checkKey = $this->array->isKey($format, 'menus_id', 'name');
        if(!$checkKey){ throw new ExceptionsRules("请填写必填参数值"); }
        $menuIds = explode(',', $format['menus_id']); // 菜单编号转为数组
        $menuIds = array_flip(array_flip($menuIds));  // 删除重复编号
        // 验证编号是否存在
        $menuIds = $this->menus->checkIds($menuIds);
        if(!count($menuIds)){ throw new ExceptionsRules("菜单不存在"); }
        $unique = md5((new Time())->millisecond().uniqid()); // 信息和信息标签的唯一值
        $format['unique'] = $unique;
        $formatMenus = []; // 菜单
        $formatMenusCount = 0; // 菜单总数
        foreach ($menuIds as $menuId){
            $formatMenus[$formatMenusCount]['unique'] = $unique; // 唯一值
            $formatMenus[$formatMenusCount]['menu_id'] = (int)$menuId; // 菜单编号
            $formatMenus[$formatMenusCount]['add_time'] = time(); // 添加时间
            $formatMenus[$formatMenusCount]['clear_time'] = time(); // 清除时间
            $formatMenus[$formatMenusCount]['is_del'] = 0; // 是否删除 删除时修改清除时间
            $formatMenusCount++; // key自增
        }
        return [$format, $formatMenus];
    }

    /**
     * 添加
     *
     * @param array $data
     * @throws ExceptionsRules
     */
    public function insert(array $data): void
    {
        // TODO: Implement insert() method.
        try {
            list($insert, $insertMenus) = $this->param($data);
            $adminId = $this->login->tokenId();
            if(!$adminId){ throw new ExceptionsRules("请先登陆"); }
            $insert['admin_id'] = $adminId;
            $insert['add_time'] = time();
            $insert['is_del'] = 0;
            $this->model->begin(); // 开启事务
            $insertStatus = true; // 规则添加状态
            $insertMenusStatus = true; // 规则菜单添加状态
            $insertErrorMessage = "添加失败"; // 添加失败提示信息
            try {
                $this->model->create($insert); // 添加规则
            }catch (Database $exception) {
                $insertStatus = false; // 重置信息添加状态
                $insertErrorMessage = $exception->getMessage(); // 重置添加失败提示信息
            }
            try {
                (new RulesMenusRepository())->insert($insertMenus); // 添加标签
            }catch (ExceptionsRulesMenus $exception) {
                $insertMenusStatus = false; // 重置规则菜单添加状态
                $insertErrorMessage = $exception->getMessage(); // 重置添加失败提示信息
            }
            $res = $insertStatus && $insertMenusStatus; // 检测添加状态
            $this->model->checkBegin($res); // 验证事务
            // 添加失败抛出异常
            if(!$res) { throw new ExceptionsRules($insertErrorMessage); }
        }catch (ExceptionsRules $exception) {
            throw new ExceptionsRules($exception->getMessage());
        }
    }

    /**
     * 修改
     *
     * @param array $data
     * @param int $id
     * @throws ExceptionsRules
     */
    public function update(array $data, int $id): void
    {
        // TODO: Implement update() method.
        // 验证编号
        $checkId = $this->model->checkId($this->model->table, $id);
        if(!$checkId){ throw new ExceptionsRules("参数错误"); }
        try {
            list($update, $insertMenus) = $this->param($data);
            $this->model->begin(); // 开启事务
            $updateStatus = true; // 规则修改状态
            $insertMenusStatus = true; // 规则菜单添加状态
            $updateErrorMessage = "修改失败"; // 修改失败提示信息
            try {
                $this->model->update($update, $id); // 修改规则
            }catch (Database $exception) {
                $updateStatus = false; // 重置信息修改状态
                $updateErrorMessage = $exception->getMessage(); // 重置修改失败提示信息
            }
            try {
                (new RulesMenusRepository())->insert($insertMenus); // 添加标签
            }catch (ExceptionsRulesMenus $exception) {
                $insertMenusStatus = false; // 重置规则菜单添加状态
                $updateErrorMessage = $exception->getMessage(); // 重置修改失败提示信息
            }
            $res = $updateStatus && $insertMenusStatus; // 检测添加状态
            $this->model->checkBegin($res); // 验证事务
            // 修改失败抛出异常
            if(!$res) { throw new ExceptionsRules($updateErrorMessage); }
        }catch (ExceptionsRules $exception) {
            throw new ExceptionsRules($exception->getMessage());
        }
    }

    /**
     * 删除
     * @param int $id
     * @throws ExceptionsRules
     */
    public function delete(int $id): void
    {
        // TODO: Implement delete() method.
        // 验证编号
        $checkId = $this->model->checkId($this->model->table, $id);
        if(!$checkId){ throw new ExceptionsRules("参数错误"); }
        // 验证父级编号，如果父级编号不存在则返回所有权限下的管理员
        $adminIds = (new AdminsRepository())->subordinateIds($this->login->tokenId()); // 获取当前管理员编号和下级管理员编号
        // 没有下级
        if(!count($adminIds)) { throw new ExceptionsRules("暂无修改权限"); }
        // 验证编号
        $checkId = $this->model->checkId($this->model->table, $id);
        if(!$checkId){ throw new ExceptionsRules("已删除，不能重复删除"); }
        try{
            $this->model->update(['is_del' => 1], $id); // 删除
        }catch (Database $exception) {
            throw new ExceptionsRules($exception->getMessage());
        }
    }

    /**
     * 菜单
     *
     * @return array
     */
    public function menus(): array
    {
        try {
            // 获取菜单
            $total = $this->menus->menus(['id', 'name', 'parent_id'], 2);
            // 格式化菜单并返回
            return (new ServiceMenus())->rules([], $total, 0);
        }catch (ExceptionsMenus $exception) {
            return  [];
        }
    }


    /**
     * 规则
     *
     * @return array
     * @throws ExceptionsRules
     */
    public function rules(): array
    {
        try {
            // 验证父级编号，如果父级编号不存在则返回所有权限下的管理员
            $adminIds = (new AdminsRepository())->subordinateIds($this->login->tokenId()); // 获取当前管理员编号和下级管理员编号
            // 没有下级
            if(!count($adminIds)) { throw new ExceptionsRules("暂无列表"); }
            return $this->model->rules($adminIds);
        }catch (Database $exception) {
            throw new ExceptionsRules($exception->getMessage());
        }
    }

    /**
     * 规则编号
     *
     * @param int $id
     * @return array
     * @throws ExceptionsRules
     */
    public function rulesMenu(int $id): array
    {
        // 验证编号
        $checkId = $this->model->checkId($this->model->table, $id);
        if(!$checkId){ throw new ExceptionsRules("参数错误"); }
        $rules =  $this->model->first($this->model->table, $id, ['menus_id']);
        $menuIds = explode(',', $rules['menus_id']); // 菜单编号转为数组
        $menuIds = array_flip(array_flip($menuIds));  // 删除重复编号
        $menuTotalIds = $this->menus->checkIds($menuIds);
        $menuIdsInfo = $this->menus->menuIdsInfo($menuTotalIds);
        return (new ServiceMenus())->rules([], $menuIdsInfo, 0);
    }
}
