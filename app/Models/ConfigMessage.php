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
 * 配置信息
 *
 * Class ConfigMessage
 * @package App\Models
 */
class ConfigMessage implements InterfacesModel
{
    use TraitsModel;

    /**
     * 表名
     *
     * @var string
     */
    public $table = 'config_message';

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
            $selects = ['id', 'name', 'description', 'select', 'type', 'type_value', 'value', 'category_id', 'add_time', 'is_show'];
            $sql = "SELECT ";
            $sql .= $this->select($selects, []);
            $sql .= " FROM ".$this->prefix . $this->table;
            $sql .= $this->where($where); // 条件
            $sql .= $this->order($order); // 排序
            $sql .= $this->limit(compact('offset', 'limit')); // 条数
            $list = $this->db::select($sql); // 执行
            // 循环获取列表并返回
            return array_map(function ($item){
                return ['id' => $item->id, 'name' => $item->name, 'description' => $item->description, 'select' => $item->select, 'type' => $item->type, 'type_value' => $item->type_value, 'value' => $item->value, 'category_id' => $item->category_id, 'is_show' => $item->is_show, 'add_time' => date('Y-m-d H:i:s', $item->add_time)];
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
     * 配置(单个)
     *
     * @param string $select
     * @return string
     */
    public function config(string $select): string
    {
        try{
            $sql = "SELECT `value` FROM ".$this->prefix . $this->table;
            $sql .= $this->where(['select' => $select]); // 条件
            $result = $this->db::select($sql); // 执行
            return is_null($result) ? '' : $result[0]->value; // 返回
        }catch (\Exception $exception){ return  ''; }
    }

    /**
     * 配置(多个)
     *
     * @param array $selects
     * @param bool $show
     * @return array
     */
    public function configs(array $selects, bool $show): array
    {
        try{
            $sql = "SELECT `select`,`value` FROM ".$this->prefix . $this->table;
            // 是否只查询前台展示配置
            if($show) { $sql .= $this->where(['is_show' => 1]); }else { $sql .= $this->where([]); }
            // 是否指定唯一值查询
            if(count($selects)) { $sql .= " AND `select` IN ('".implode("','", $selects)."')";  }
            $result = $this->db::select($sql); // 执行
            $format = []; // 格式化配置
            array_map(function ($item) use(&$format){ $format[$item->select] = $item->value; }, $result);
            return $format; // 返回
        }catch (\Exception $exception){ return []; }
    }
}
