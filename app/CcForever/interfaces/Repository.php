<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/7/21
 */

namespace App\CcForever\interfaces;

/**
 * Interface Repository
 * @package App\CcForever\interfaces
 */
interface Repository
{
    public function lst(array $where, array $order, int $page, int $limit): array; // 列表

    public function count(array $where): int; // 总数

    public function insert(array $data): void; // 添加

    public function update(array $data, int $id): void; // 修改

    public function delete(int $id): void; // 删除
}
