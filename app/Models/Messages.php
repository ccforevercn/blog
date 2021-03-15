<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/8/3
 */

namespace App\Models;

use App\CcForever\{
    interfaces\Model as InterfacesModel, traits\Model as TraitsModel
};
use App\Exceptions\Database;

/**
 * 信息
 *
 * Class Messages
 * @package App\Models
 */
class Messages implements InterfacesModel
{
    use TraitsModel;

    /**
     * 表名
     *
     * @var string
     */
    public $table = 'messages';

    private $basic = ['id', 'name', 'columns_id', 'image', 'keywords', 'description', 'update_time'];

    private $attach = ['writer', 'click', 'weight', 'index', 'hot', 'add_time', 'page', 'unique'];

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
            $selects = array_merge($this->basic, $this->attach);
            // 查询字段(select)
            $selectsSql = [',(SELECT `name` FROM ' . $this->prefix.(new Columns())->table . ' WHERE `id` = '.$this->prefix.$this->table.'.`columns_id` AND `is_del` = 0) as `cname`'];
            $sql = "SELECT ";
            $sql .= $this->select($selects, $selectsSql);
            $sql .= " FROM ".$this->prefix . $this->table;
            $sql .= $this->where($where); // 条件
            $sql .= $this->order($order); // 排序
            $sql .= $this->limit(compact('offset', 'limit')); // 条数
            $list = $this->db::select($sql); // 执行
            // 循环获取列表并返回
            return array_map(function ($item){
                return ['id' => $item->id, 'name' => $item->name, 'columns_id' => $item->columns_id, 'cname' => $item->cname,'image' => $item->image, 'keywords' => $item->keywords, 'description' => $item->description, 'weight' => $item->weight, 'click' => $item->click, 'writer' => $item->writer, 'index' => $item->index, 'hot' => $item->hot, 'page' => $item->page, 'unique' => $item->unique, 'add_time' => date('Y-m-d H:i', $item->add_time), 'update_time' => date('Y-m-d H:i', $item->update_time)];
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
     * 信息(包含内容和标签)
     *
     * @param array $select
     * @param array $where
     * @param array $order
     * @param array $limits
     * @return array
     * @throws Database
     */
    public function messages(array $select, array $where, array $order, array $limits): array
    {
        try{
            // 查询字段(select)
            $selectsSql = [',(SELECT `name` FROM ' . $this->prefix.(new Columns())->table . ' WHERE `id` = '.$this->prefix.$this->table.'.`columns_id` AND `is_del` = 0) as `cname`', 'IFNULL((select `content` FROM ' . $this->prefix.(new MessagesContent())->table . ' WHERE `id` = '.$this->prefix.$this->table.'.`id`),"暂无内容") as content', 'IFNULL((select `images` FROM ' . $this->prefix.(new MessagesContent())->table . ' WHERE `id` = '.$this->prefix.$this->table.'.`id`), "") as images', 'IFNULL((SELECT GROUP_CONCAT(`name`) from '. $this->prefix.(new Tags())->table .' WHERE id IN(SELECT `tag_id` FROM '. $this->prefix.(new MessagesTags())->table .' where `unique` = '.$this->prefix.$this->table.'.`unique`)), "") as tag_name'];
            $sql = "SELECT ";
            $sql .= $this->select($select, $selectsSql);
            $sql .= " FROM ".$this->prefix . $this->table;
            $sql .= $this->where($where); // 条件
            $sql .= $this->order($order); // 排序
            $sql .= $this->limit($limits); // 条数
            $list = $this->db::select($sql); // 执行
            $selects = array_merge($select, ['cname', 'content', 'images', 'tag_name']);
            // 获取参数并返回
            return array_map(function ($item) use($selects){
                $message = [];
                foreach ($selects as $select) { $message[$select] = $item->$select; }
                return  $message;
            },$list);
        }catch (\Exception $exception){
            throw new Database($exception->getMessage());
        }
    }

    /**
     * 分页(上一页、下一页)
     *
     * @param array $selects
     * @param array $wheres
     * @param array $order
     * @return array
     * @throws Database
     */
    public function paging(array $selects, array $wheres, array $order): array
    {
        try {
            $sql = "SELECT ";
            $sql .= $this->select($selects, []);
            $sql .= " FROM ".$this->prefix . $this->table;
            $sql .= $this->where([]); // 条件
            foreach ($wheres as $where) { $sql .= " AND ".$this->prefix . $this->table.'.`'.$where['select'].'` '. $where['condition'] . ' ' . $where['value']; }
            $sql .= $this->order($order); // 排序
            $result = $this->db::selectOne($sql); // 执行
            return is_null($result) ? [] : (array)$result;
        }catch (\Exception $exception){
            throw new Database($exception->getMessage());
        }
    }

    /**
     * 信息(上一页和下一页)
     *
     * @param int $columnId
     * @param array $order
     * @param int $value
     * @return array
     */
    public static function messageEnter(int $columnId, array $order, int $value): array
    {
        $model = new self;
        $model = $model->select(self::GetAlias().'id', self::GetAlias().'name', self::GetAlias().'page');
        $model = $model->where(self::GetAlias().'columns_id', $columnId);
        $model = $model->where(self::GetAlias().'is_del', 0);
        $model = $model->where(self::GetAlias().'release', 1);
        $model = $model->where(self::GetAlias().$order['select'], $order['condition'], $value);
        $model = $model->orderBy(self::GetAlias().$order['select'], $order['value']);
        $model = $model->limit(1);
        return $model->get()->toArray();
    }

}
