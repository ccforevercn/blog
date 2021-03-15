<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/7/21
 */

namespace App\Models;

use App\Exceptions\Database;
use App\CcForever\{
    interfaces\Model as InterfacesModel, traits\Model as TraitsModel
};

/**
 * 菜单
 *
 * Class Menus
 * @package App\Models
 */
class Menus implements InterfacesModel
{
    use TraitsModel;

    /**
     * 表名
     *
     * @var string
     */
    public $table = 'menus';

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
            $joinAs = "ccparent"; // join表别名
            // 查询字段
            $selectSql = [$this->prefix . $this->table.'.`id`', $this->prefix . $this->table.'.`name`',$this->prefix . $this->table.'.`parent_id`',$this->prefix . $this->table.'.`routes`',$this->prefix . $this->table.'.`page`',$this->prefix . $this->table.'.`icon`',$this->prefix . $this->table.'.`menu`',$this->prefix . $this->table.'.`sort`',$this->prefix . $this->table.'.`add_time`', 'IFNULL('.$joinAs.'.`name`, "顶级菜单") as `parentname`'];
            $sql = "SELECT ";
            $sql .= $this->select([], $selectSql);
            $sql .= " FROM ".$this->prefix . $this->table;
            $sql .= $this->join('LEFT JOIN', $this->prefix . $this->table, $joinAs, $joinAs . '.`id` = '.$this->prefix . $this->table. '.`parent_id`');
            $sql .= $this->where($where); // 条件
            $sql .= $this->order($order); // 排序
            $sql .= $this->limit(compact('offset', 'limit')); // 条数
            $list = $this->db::select($sql); // 执行
            // 返回列表
            return array_map(function ($item){
                return ['id' => $item->id, 'name' => $item->name, 'parent_id' => $item->parent_id, 'routes' => $item->routes, 'page' => $item->page, 'icon' => $item->icon, 'menu' => $item->menu, 'sort' => $item->sort, 'add_time' => date('Y-m-d H:i', $item->add_time), 'parentname' => $item->parentname];
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
