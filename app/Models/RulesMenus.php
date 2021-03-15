<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2021/2/24
 */

namespace App\Models;

use App\Exceptions\Database;
use App\CcForever\{
    interfaces\Model as InterfacesModel, traits\Model as TraitsModel
};

/**
 * 规则菜单
 *
 * Class RulesMenus
 * @package App\Models
 */
class RulesMenus implements InterfacesModel
{
    use TraitsModel;

    public $table = "rules_menus";

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

    }

    public function count(array $where): int
    {
        // TODO: Implement count() method.

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


    public function update(array $update, int $id): void
    {
        // TODO: Implement update() method.

    }
}
