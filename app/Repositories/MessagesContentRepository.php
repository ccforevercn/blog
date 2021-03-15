<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @date:    2021/2/26
 */

namespace App\Repositories;

use App\Models\MessagesContent;
use App\Exceptions\{
    MessagesContent as ExceptionsMessagesContent, Database
};
use App\CcForever\{
    interfaces\Repository as InterfacesRepository,
    traits\Repository as TraitsRepository
};

/**
 * 信息内容
 *
 * Class MessagesContentRepository
 * @package App\Repositories
 */
class MessagesContentRepository implements InterfacesRepository
{
    use TraitsRepository;

    public function __construct()
    {
        $this->model = new MessagesContent();
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
     * @throws ExceptionsMessagesContent
     */
    public function insert(array $data): void
    {
        // TODO: Implement insert() method.
        try {
            $this->model->create($data);
        }catch (Database $exception) {
            throw new ExceptionsMessagesContent($exception->getMessage());
        }
    }

    /**
     * 修改
     *
     * @param array $data
     * @param int $id
     * @throws ExceptionsMessagesContent
     */
    public function update(array $data, int $id): void
    {
        // TODO: Implement update() method.
        try {
            $this->model->update($data, $id);
        }catch (Database $exception) {
            throw new ExceptionsMessagesContent($exception->getMessage());
        }
    }

    public function delete(int $id): void
    {
        // TODO: Implement delete() method.
    }

    /**
     * 查询单条
     *
     * @param int $id
     * @param array $selects
     * @return string[]
     */
    public function message(int $id, array $selects): array
    {
        $content = $this->model->first($this->model->table, $id, $selects);
        return count($content) ? $content : array_map(function (){ return ''; },array_flip($selects));
    }
}
