<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @date:    2021/2/26
 */

namespace App\Repositories;

use App\Models\MessagesTags;
use App\Exceptions\{
    MessagesTags as ExceptionsMessagesTags, Database
};
use App\CcForever\{
    interfaces\Repository as InterfacesRepository,
    traits\Repository as TraitsRepository
};

/**
 * 信息标签
 *
 * Class MessagesTagsRepository
 * @package App\Repositories
 */
class MessagesTagsRepository implements InterfacesRepository
{
    use TraitsRepository;

    public function __construct()
    {
        $this->model = new MessagesTags();
    }

    public function lst(array $where, array $order, int $page, int $limit): array
    {
        // TODO: Implement lst() method.
        return $this->model->lst($where, $order, $page, $limit);
    }

    public function count(array $where): int
    {
        // TODO: Implement count() method.
        return $this->model->count($where);
    }

    /**
     * 添加
     *
     * @param array $data
     * @throws ExceptionsMessagesTags
     */
    public function insert(array $data): void
    {
        // TODO: Implement insert() method.
        try {
            $this->model->create($data);
        }catch (Database $exception) {
            throw new ExceptionsMessagesTags($exception->getMessage());
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

    /**
     * 标签
     *
     * @param int $id
     * @return array
     * @throws ExceptionsMessagesTags
     */
    public function tags(int $id): array
    {
        try {
            return $this->model->tags($id);
        }catch (Database $exception) {
            throw new ExceptionsMessagesTags($exception->getMessage());
        }
    }
}
