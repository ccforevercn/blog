<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/7/21
 */

namespace App\CcForever\traits;

/**
 * Trait Repository
 * @package App\CcForever\traits
 */
trait Repository
{

    private $model;

    /**
     * 验证编号
     *
     * @param int $id
     * @return bool
     */
    public function checkId(int $id): bool
    {
        return $this->model->checkId($this->model->table, $id);
    }
}
