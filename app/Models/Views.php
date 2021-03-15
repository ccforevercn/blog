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
 * 视图
 *
 * Class Views
 * @package App\Models
 */
class Views implements InterfacesModel
{
    use TraitsModel;

    /**
     * 表名
     *
     * @var string
     */
    public $table = 'views';

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
            $selects = ['id', 'name', 'add_time', 'path', 'type'];
            $sql = "SELECT ";
            $sql .= $this->select($selects, []);
            $sql .= " FROM ".$this->prefix . $this->table;
            $sql .= $this->where($where); // 条件
            $sql .= $this->order($order); // 排序
            $sql .= $this->limit(compact('offset', 'limit')); // 条数
            $list = $this->db::select($sql); // 执行
            // 循环获取列表并返回
            return array_map(function ($item){
                return ['id' => $item->id, 'name' => $item->name, 'add_time' => date('Y-m-d H:i', $item->add_time), 'path' => $item->path, 'type' => $item->type, 'type_name' => $item->type === 1 ? "栏目" : "信息"];
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
}
