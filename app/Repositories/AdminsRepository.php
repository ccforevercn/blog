<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/7/20
 */

namespace App\Repositories;

use App\Models\Admins as ModelAdmins;
use App\Exceptions\{
    Admins as ExceptionsAdmins, Database
};
use App\CcForever\{
    interfaces\Repository as InterfacesRepository,
    service\Check,
    service\HandleArray,
    service\Jwt,
    service\model\Admins as ServiceAdmins,
    service\Login,
    traits\Repository as TraitsRepository
};

/**
 * 管理员
 *
 * Class AdminsRepository
 * @package App\Repositories
 */
class AdminsRepository implements InterfacesRepository
{
    use TraitsRepository;

    /**
     * 管理员服务类
     *
     * @var ServiceAdmins
     */
    private $admin;

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
     * 规则类
     *
     * @var RulesRepository
     */
    private $rulesRepository;

    /**
     * 登陆处理类
     *
     * @var Login
     */
    protected $login;

    public function __construct()
    {
        $this->model = new ModelAdmins();
        $this->check = new Check();
        $this->login = new Login();
        $this->array = new HandleArray();
        $this->admin = new ServiceAdmins();
        $this->rulesRepository = new RulesRepository();
    }

    /**
     * 登陆
     *
     * @param string $username
     * @param string $password
     * @return array
     * @throws ExceptionsAdmins
     */
    public function login(string $username, string $password): array
    {
        $message = $this->model->username($username); // 管理员信息
        if(!count($message)) { throw new ExceptionsAdmins("未知账号"); } // 账号不存在
        if(!$message['status']){ throw new ExceptionsAdmins("账号锁定"); } // 账号锁定
        // 密码错误
        if($this->check->password($password) !== $message['password']){ throw new ExceptionsAdmins("密码错误"); }
        // 添加登陆记录
        if($message['login_count'] < 9999) { $message['login_count']++; }
        $this->model->loginCount($message['id'], $message['login_count']);
        // 获取token
        $message['token'] = (new Jwt())->get(['id' => $message['id'], 'username' => $message['username'], 'found' => $message['found'], 'lander' => 'admin']);
        $this->array->unsetElements($message, 'password', 'is_del', 'found'); // 删除多余字段
        return $message; // 返回用户信息
    }

    /**
     * 列表
     *
     * @param array $where
     * @param array $order
     * @param int $page
     * @param int $limit
     * @return array
     * @throws ExceptionsAdmins
     */
    public function lst(array $where, array $order, int $page, int $limit): array
    {
        // TODO: Implement lst() method.
        try{
            // 验证父级编号，如果父级编号不存在则返回所有权限下的管理员
            $parentIds = $this->subordinateIds($this->login->tokenId()); // 获取当前管理员编号和下级管理员编号
            // 没有下级
            if(!count($parentIds)) { throw new ExceptionsAdmins("暂无列表"); }
            $offset = $this->check->page($page, $limit); // 分页起始值
            $wheres = $this->array->getNotNull($where, 'string',['parent_id', 'int'], ['username']);
            // 父级编号不存在时
            if(!$this->array->isKey($wheres, 'parent_id')) { $wheres['parent_id'] = $parentIds; }
            return $this->model->lst($wheres, $order, $offset, $limit); // 查询并返回
        }catch (Database $exception){
            throw new ExceptionsAdmins($exception->getMessage());
        }
    }

    /**
     * 总数
     *
     * @param array $where
     * @return int
     * @throws ExceptionsAdmins
     */
    public function count(array $where): int
    {
        // TODO: Implement count() method.
        try{
            // 验证父级编号，如果父级编号不存在则返回所有权限下的管理员
            $parentIds = $this->subordinateIds($this->login->tokenId()); // 获取当前管理员编号和下级管理员编号
            // 没有下级
            if(!count($parentIds)) { throw new ExceptionsAdmins("暂无列表"); }
            $wheres = $this->array->getNotNull($where, 'string',['parent_id', 'int'], ['username']);
            // 父级编号不存在时
            if(!$this->array->isKey($wheres, 'parent_id')) { $wheres['parent_id'] = $parentIds; }
            return $this->model->count($wheres);// 查询并返回
        }catch (Database $exception){
            throw new ExceptionsAdmins($exception->getMessage());
        }
    }

    /**
     * 添加
     *
     * @param array $data
     * @throws ExceptionsAdmins
     */
    public function insert(array $data): void
    {
        // TODO: Implement insert() method.
        $load = $this->login->tokenParam(); // 登陆信息
        if(!$load['found']){ throw new ExceptionsAdmins("没有权限创建管理员"); } // 没有权限创建管理员
        // 重置插入数据
        $insert = $this->array->getNotNull($data, 'string', ['username'], ['password'], ['real_name'], ['status', 'int'], ['found', 'int'], ['rule_id', 'int'], ['email']);
        $insert['parent_id'] = (int)$load['id']; // 上级编号
        // 验证必要编号是否存在
        $checkKey = $this->array->isKey($data, 'username', 'password', 'real_name', 'status', 'found', 'rule_id', 'email');
        if(!$checkKey){ throw new ExceptionsAdmins("请填写必填参数值"); }
        // 账号已被注册
        if(count($this->model->username($insert['username']))){ throw new ExceptionsAdmins("账号已被注册"); }
        // 验证规则编号是否存在
        $checkRuleId = $this->rulesRepository->checkId((int)$insert['rule_id']);
        // 规则编号不存在
        if(!$checkRuleId){ throw new ExceptionsAdmins("规则不存在"); }
        // 设置时间
        $insert['add_time'] = $insert['last_time'] =  time();
        // 设置登陆IP
        $insert['add_ip'] = $insert['last_ip'] = app('request')->ip();
        // 设置登陆次数和是否删除
        $insert['login_count'] = $insert['is_del'] = 0;
        // 插入数据库
        try{
            $this->model->create($insert);
        }catch (Database $exception){
            throw new ExceptionsAdmins($exception->getMessage());
        }
    }

    /**
     * 修改
     *
     * @param array $data
     * @param int $id
     * @throws ExceptionsAdmins
     */
    public function update(array $data, int $id): void
    {
        // TODO: Implement update() method.
        // 验证编号
        $checkId = $this->model->checkId($this->model->table, $id);
        if(!$checkId) { throw new ExceptionsAdmins("参数错误"); }
        // 重置修改数据
        $update = $this->array->getNotNull($data, 'string', ['password'], ['real_name'], ['status', 'int'], ['found', 'int'], ['rule_id', 'int'], ['email']);
        if($this->login->tokenId() === $id) {
            // 验证必要编号是否存在
            $checkKey = $this->array->isKey($update,'real_name', 'email');
        }else{
            // 验证必要编号是否存在
            $checkKey = $this->array->isKey($update,'real_name', 'status', 'found', 'rule_id', 'email');
        }
        if(!$checkKey){ throw new ExceptionsAdmins("请填写必填参数值"); }
        // 修改密码
        if(array_key_exists('password', $update)){
            // 验证密码长度
            $checkPassword = strlen($update['password']) < 8 || strlen($update['password']) > 18;
            if($checkPassword) { throw new ExceptionsAdmins("密码至少是8个字符，最多18个字符"); }
            // 管理员密码加密
            $update['password'] = $this->check->password($update['password']);
        }else{
            unset($update['password']);
        }
        // 验证修改数据和数据库数据是否一致
        $checkUpdate = $this->model->first($this->model->table, $id, array_keys($update)) === $update;
        if($checkUpdate) { return; }
        // 验证规则编号是否存在
        if(array_key_exists('rule_id', $update)) {
            $checkRuleId = $this->rulesRepository->checkId($update['rule_id']);
            if(!$checkRuleId){ throw new ExceptionsAdmins("规则不存在"); } // 规则编号不存在
        }
        // 下级编号
        $subordinateIds = $this->subordinateIds($this->login->tokenId());
        // 验证是否可以修改
        if(!in_array($id, $subordinateIds)){ throw new ExceptionsAdmins("权限不足"); }
        // 修改数据并返回
        try{
            $this->model->update($update, $id);
        }catch (Database $exception) {
            throw new ExceptionsAdmins($exception->getMessage());
        }
    }

    /**
     * 删除
     *
     * @param int $id
     * @throws ExceptionsAdmins
     */
    public function delete(int $id): void
    {
        // TODO: Implement delete() method.
        // 验证编号
        $checkId = $this->model->checkId($this->model->table, $id);
        if(!$checkId) { throw new ExceptionsAdmins("已删除，不能重复删除"); }
        // 下级编号
        $subordinateIds = $this->subordinateIds($this->login->tokenId());
        // 验证是否可以修改
        if(!in_array($id, $subordinateIds)){ throw new ExceptionsAdmins("权限不足"); }
        try{
            // 删除数据
            $this->model->update(['is_del' => 1], $id);
        }catch (Database $exception) {
            throw new ExceptionsAdmins($exception->getMessage());
        }
    }

    /**
     * 下级编号
     *
     * @param int $id
     * @return array
     */
    public function subordinateIds(int $id): array
    {
        // 所有管理员编号
        $adminIds = $this->model->get($this->model->table, ['id', 'parent_id']);
        // 当前管理员和下级管理员
        return $this->admin->subordinateIds($adminIds, [$id]);
    }

    /**
     * 菜单
     *
     * @param int $id
     * @param array $selects
     * @param int $menu
     * @return array
     */
    public function menus(int $id, array $selects, int $menu): array
    {
        return $this->model->menus($id, $selects, $menu);
    }
}
