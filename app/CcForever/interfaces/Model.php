<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/7/21
 */

namespace App\CcForever\interfaces;

/**
 * Interface Model
 * @package App\CcForever\interfaces
 */
interface Model
{
    public function lst(array $where, array $order, int $offset, int $limit) :array; // 列表

    public function count(array $where): int; // 总数

    public function create(array $create): void; // 创建

    public function update(array $update, int $id): void; // 修改
}
