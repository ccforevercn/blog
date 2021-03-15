<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/7/23
 */

namespace App\Models;

use App\Exceptions\Database;
use App\CcForever\{
    interfaces\Model as InterfacesModel, traits\Model as TraitsModel
};

/**
 * 规则
 *
 * Class Rules
 * @package App\Models
 */
class Rules implements InterfacesModel
{
    use TraitsModel;

    public $table = 'rules'; // 表名称

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
            $joinAs = 'admin'; // 管理员表别名
            // 查询字段
            $selectSql = [$this->prefix . $this->table.'.`id`', $this->prefix . $this->table.'.`name`', $this->prefix . $this->table.'.`menus_id`', $this->prefix . $this->table.'.`unique`', $this->prefix . $this->table.'.`admin_id`', $this->prefix . $this->table.'.`add_time`', '('.$joinAs.'.`real_name`) as admin_name'];
            $sql = "SELECT ";
            $sql .= $this->select([], $selectSql);
            $sql .= " FROM ".$this->prefix . $this->table;
            $sql .= $this->join('LEFT JOIN', $this->prefix .(new Admins())->table, $joinAs, $joinAs . '.`id` = '.$this->prefix . $this->table. '.`admin_id`');
            $sql .= $this->where($where); // 条件
            $sql .= $this->order($order); // 排序
            $sql .= $this->limit(compact('offset', 'limit')); // 条数
            $list = $this->db::select($sql); // 执行
            // 返回列表
            return array_map(function ($item){
                return ['id' => $item->id, 'name' => $item->name, 'menus_id' => $item->menus_id, 'unique' => $item->unique, 'admin_id' => $item->admin_id, 'admin_name' => $item->admin_name, 'add_time' => date('Y-m-d H:i:s', $item->add_time)];
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
            $sql .= $this->order(['id' => 'DESC']); // 排序
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

    /**
     * 规则
     *
     * @param array $adminIds
     * @return array
     * @throws Database
     */
    public function rules(array $adminIds): array
    {
        try{
            $sql = "SELECT ";
            $sql .= $this->select(['id', 'name'], []);
            $sql .= " FROM ".$this->prefix . $this->table;
            $sql .= $this->where([]); // 条件
            $sql .= " AND `admin_id` IN (". implode(',', $adminIds) .")"; // 添加管理员编号条件
            $sql .= $this->order(['id' => 'DESC']); // 排序
            $list = $this->db::select($sql); // 执行
            // 返回列表
            return array_map(function ($item){ return ['id' => $item->id, 'name' => $item->name]; }, $list);
        }catch (\Exception $exception){
            throw new Database($exception->getMessage());
        }
    }
}
