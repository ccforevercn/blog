<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @date:    2021/3/1
 */

namespace App\Repositories;

use App\Models\RulesMenus;
use App\Exceptions\{
    RulesMenus as ExceptionsRulesMenus, Database
};
use App\CcForever\{
    interfaces\Repository as InterfacesRepository,
    traits\Repository as TraitsRepository
};

/**
 * 规则菜单
 *
 * Class RulesMenusRepository
 * @package App\Repositories
 */
class RulesMenusRepository implements InterfacesRepository
{
    use TraitsRepository;

    public function __construct()
    {
        $this->model = new RulesMenus();
    }

    public function lst(array $where, array $order, int $page, int $limit): array
    {
        // TODO: Implement lst() method.
        return [];
    }
    public function count(array $where): int
    {
        // TODO: Implement count() method.
        return 0;
    }

    /**
     * 添加
     *
     * @param array $data
     * @throws ExceptionsRulesMenus
     */
    public function insert(array $data): void
    {
        // TODO: Implement insert() method.
        try {
            $this->model->create($data);
        }catch (Database $exception) {
            throw new ExceptionsRulesMenus($exception->getMessage());
        }
    }
    public function update(array $data, int $id): void
    {
        // TODO: Implement update() method.
    }
    public function delete(int $id): void
    {
        // TODO: Implement delete() method.
    }
}
