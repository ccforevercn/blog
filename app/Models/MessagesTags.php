<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @date:    2021/2/26
 */

namespace App\Models;

use App\CcForever\{
    interfaces\Model as InterfacesModel, traits\Model as TraitsModel
};
use App\Exceptions\Database;

/**
 * 信息标签
 *
 * Class MessagesTags
 * @package App\Models
 */
class MessagesTags implements InterfacesModel
{
    use TraitsModel;

    /**
     * 表名
     *
     * @var string
     */
    public $table = 'messages_tags';

    /**
     * 列表
     *
     * @param array $where
     * @param array $order
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function lst(array $where, array $order, int $offset, int $limit): array
    {
        // TODO: Implement lst() method.
        return  [];
    }

    /**
     * 总数
     *
     * @param array $where
     * @return int
     */
    public function count(array $where): int
    {
        // TODO: Implement count() method.
        return  0;
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
            $bool = $this->inserts($this->table, $create);
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
     * 标签
     *
     * @param int $id
     * @return array
     * @throws Database
     */
    public function tags(int $id): array
    {
        $uniqueSql = "select `unique` from cc_messages where is_del = 0 and id = ?";
        $tagIdSql = "select tag_id from cc_messages_tags where is_del = 0 and `unique` = (". $uniqueSql .")";
        $sql = "select name,id from cc_tags where is_del = 0 and id in (". $tagIdSql .")";
        try{
            $tags = $this->db::select($sql, [$id]);
            return  is_null($tags) ? [] : (array)$tags;
        }catch (\Exception $exception){
            throw new Database($exception->getMessage());
        }
    }
}
