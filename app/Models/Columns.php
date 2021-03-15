<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/8/4
 */

namespace App\Models;

use App\CcForever\{
    interfaces\Model as InterfacesModel, traits\Model as TraitsModel
};
use App\Exceptions\Database;

/**
 * 栏目
 *
 * Class Columns
 * @package App\Models
 */
class Columns implements InterfacesModel
{
    use TraitsModel;

    /**
     * 表名
     *
     * @var string
     */
    public $table = 'columns';

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
            // 查询字段(select)
            $selectsSql = [$this->prefix.$this->table.'.`id`', $this->prefix.$this->table.'.`name`', $this->prefix.$this->table.'.`name_alias`', $this->prefix.$this->table.'.`parent_id`', $this->prefix.$this->table.'.`image`', $this->prefix.$this->table.'.`banner_image`', $this->prefix.$this->table.'.`keywords`', $this->prefix.$this->table.'.`description`', $this->prefix.$this->table.'.`add_time`', $this->prefix.$this->table.'.`weight`', $this->prefix.$this->table.'.`limit`', $this->prefix.$this->table.'.`sort`', $this->prefix.$this->table.'.`navigation`', $this->prefix.$this->table.'.`render`', $this->prefix.$this->table.'.`page`', 'IFNULL('.$joinAs.'.`name`, "顶级菜单") as `parentname`'];
            $sql = "SELECT ";
            $sql .= $this->select([], $selectsSql);
            $sql .= " FROM ".$this->prefix . $this->table;
            $sql .= $this->join('LEFT JOIN', $this->prefix . $this->table, $joinAs, $joinAs . '.`id` = '.$this->prefix . $this->table. '.`parent_id`');
            $sql .= $this->where($where); // 条件
            $sql .= $this->order($order); // 排序
            $sql .= $this->limit(compact('offset', 'limit')); // 条数
            $list = $this->db::select($sql); // 执行
            // 循环获取列表并返回
            return array_map(function ($item){
                return ['id' => $item->id, 'name' => $item->name, 'name_alias' => $item->name_alias, 'parent_id' => $item->parent_id, 'parentname' => $item->parentname,'image' => $item->image, 'banner_image' => $item->banner_image, 'keywords' => $item->keywords, 'description' => $item->description, 'weight' => $item->weight, 'limit' => $item->limit, 'sort' => $item->sort, 'navigation' => $item->navigation, 'render' => $item->render, 'page' => $item->page, 'add_time' => date('Y-m-d H:i:s', $item->add_time)];
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
            $sql = "SELECT COUNT(id) as count FROM ".$this->prefix . $this->table;
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
        try{
            $bool = $this->insert($this->table, $create);
            if(!$bool) { throw new Database("添加失败"); }
        }catch (\Exception $exception){
            throw new Database($exception->getMessage());
        }
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
        try{
            $bool = $this->updated($this->table, $update, $id);
            if(!$bool) { throw new Database("修改失败"); }
        }catch (\Exception $exception){
            throw new Database($exception->getMessage());
        }
    }

    /**
     * 栏目列表
     *
     * @param array $ids
     * @return array
     * @throws Database
     */
    public function messages(array $ids): array
    {
        try{
            // 查询字段
            $selects = ['id', 'name', 'name_alias', 'image', 'banner_image', 'keywords', 'parent_id', 'limit', 'render', 'sort', 'description', 'page'];
            // 查询字段(select)
            $selectsSql = [',IFNULL((SELECT `content` FROM '.$this->prefix.(new ColumnsContent())->table.' where `id` = '.$this->prefix.$this->table.'.id), "暂无内容") as `content`'];
            $sql = "SELECT ";
            $sql .= $this->select($selects, $selectsSql);
            $sql .= " FROM ".$this->prefix . $this->table;
            $sql .= $this->where(['id' => $ids]); // 条件
            $sql .= $this->order([]); // 排序
            $columns = $this->db::select($sql); // 执行
            // 获取栏目列表并返回
            return array_map(function ($item){
                return ['id' => $item->id, 'name' => $item->name, 'name_alias' => $item->name_alias, 'parent_id' => $item->parent_id,'image' => $item->image, 'banner_image' => $item->banner_image, 'keywords' => $item->keywords, 'description' => $item->description, 'limit' => $item->limit, 'sort' => $item->sort, 'render' => $item->render, 'page' => $item->page, 'content' => $item->content];
            }, $columns);
        }catch (\Exception $exception){
            throw new Database($exception->getMessage());
        }
    }
}
