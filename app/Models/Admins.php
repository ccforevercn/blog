<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/7/20
 */

namespace App\Models;

use App\CcForever\{
    interfaces\Model as InterfacesModel, traits\Model as TraitsModel
};
use App\Exceptions\Database;

/**
 * 管理员
 *
 * Class Admins
 * @package App\Models
 */
class Admins implements InterfacesModel
{
    use TraitsModel;

    /**
     * 表名
     *
     * @var string
     */
    public $table = "admins";

    /**
     * 账号信息
     *
     * @param string $username
     * @return array
     */
    public function username(string $username): array
    {
        $select = "id,username,password,real_name,status,found,email,login_count,is_del";
        $sql = "SELECT ". $select ." FROM " . $this->prefix.$this->table . " WHERE is_del = 0 AND username = ? LIMIT 1";
        $message = $this->db::selectOne($sql, [$username]);
        return  is_null($message) ? [] : (array)$message;
    }

    /**
     * 登陆次数累加
     *
     * @param int $id
     * @param int $count
     */
    public function loginCount(int $id, int $count): void
    {
        $sql = "UPDATE " . $this->prefix.$this->table . " SET login_count = ?, last_time = ?, last_ip = ? WHERE is_del = 0 AND id = ? ";
        // 执行全部SQL
        $this->db::update($sql, [$count, time(), app('request')->ip(), $id]);
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
        // 获取规则编号SQL
        $ruleSql = "SELECT rule_id FROM " . $this->prefix.$this->table . " WHERE is_del = 0 AND id = ?";
        // 获取规则唯一值SQL
        $uniqueSql = "SELECT `unique` FROM " . $this->prefix.(new Rules())->table . " WHERE is_del = 0 AND id = (" . $ruleSql . ")";
        //  获取规则菜单SQL
        $menuSql = "SELECT menu_id FROM " . $this->prefix.(new RulesMenus())->table . " WHERE is_del = 0 AND `unique` = (" . $uniqueSql . ")";
        // 获取菜单路由SQL
        $sql = "SELECT `". implode('`,`', $selects) ."` FROM " . $this->prefix.(new Menus())->table;
        $sql .= " WHERE is_del = 0"; // 是否删除
        // 是否菜单
        if($menu === 1 || $menu === 0) { $sql .= " AND menu = ". $menu; }
        // 编号
        $sql .= " AND id IN (" . $menuSql . ") ORDER BY sort DESC";
        // 执行全部SQL
        $menus = $this->db::select($sql, [$id]);
        // 获取参数并返回
        return array_map(function ($item) use($selects){
            switch (count($selects)) {
                case 1:
                    $select = $selects[0];
                    return $item->$select;
                default:
                    $menu = [];
                    foreach ($selects as $select) { $menu[$select] = $item->$select; }
                    return  $menu;
            }
        },$menus);
    }

    /**
     * 列表
     *
     * @param array $where
     * @param array $order
     * @param int $offset
     * @param int $limit
     * @return array
     * @throws Database
     */
    public function lst(array $where, array $order, int $offset, int $limit): array
    {
        // TODO: Implement lst() method.
        try{
            // 查询字段
            $selects = ['id', 'real_name','username','rule_id','email','status','add_time','last_time'];
            // 查询字段(select)
            $selectsSql = [',IFNULL((SELECT `name` FROM ' . $this->prefix.(new Rules())->table . ' WHERE `id` = '.$this->prefix.$this->table .'.`rule_id` AND `is_del` = 0), "超级管理员") as `rulename`'];
            $sql = "SELECT ";
            $sql .= $this->select($selects, $selectsSql);
            $sql .= " FROM ".$this->prefix . $this->table;
            $sql .= $this->where($where); // 条件
            $sql .= $this->order($order); // 排序
            $sql .= $this->limit(compact('offset', 'limit')); // 条数
            $list = $this->db::select($sql); // 执行
            // 返回列表
            return array_map(function ($item){
                return ['id' => $item->id, 'real_name' => $item->real_name, 'username' => $item->username, 'rule_id' => $item->rule_id, 'email' => $item->email, 'status' => $item->status, 'add_time' => date('Y-m-d H:i', $item->add_time), 'last_time' => date('Y-m-d H:i', $item->last_time), 'rulename' => $item->rulename];
            }, $list);
        }catch (\Exception $exception){
            throw new Database($exception->getMessage());
        }
    }

    /**
     * 总数
     *
     * @param array $where
     * @return int
     * @throws Database
     */
    public function count(array $where): int
    {
        // TODO: Implement count() method.
        try{
            $sql = "SELECT ";
            $sql .= "COUNT(id) as count FROM ".$this->prefix . $this->table;
            $sql .= $this->where($where); // 条件
            $result = $this->db::select($sql); // 执行
            return is_null($result) ? 0 : $result[0]->count; // 返回总数
        }catch (\Exception $exception){
            throw new Database($exception->getMessage());
        }
    }

    /**
     * 添加
     *
     * @param array $create
     * @throws Database
     */
    public function create(array $create): void
    {
        // TODO: Implement create() method.
        $bool = $this->insert($this->table, $create);
        if(!$bool) { throw new Database("添加失败"); }
    }

    /**
     * 修改
     *
     * @param array $update
     * @param int $id
     * @throws Database
     */
    public function update(array $update, int $id): void
    {
        // TODO: Implement update() method.
        $bool = $this->updated($this->table, $update, $id);
        if(!$bool) { throw new Database("修改失败"); }
    }
}
