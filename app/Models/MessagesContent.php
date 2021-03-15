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
 * 信息内容
 *
 * Class MessagesContent
 * @package App\Models
 */
class MessagesContent implements InterfacesModel
{
    use TraitsModel;

    /**
     * 表名
     *
     * @var string
     */
    public $table = "messages_content";

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
        return [];
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
        return 0;
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
