<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2021/2/23
 */

namespace App\CcForever\service\model;

/**
 * 管理员服务
 *
 * Class Admins
 * @package App\CcForever\service\model
 */
class Admins
{
    /**
     * 下级编号
     *
     * @param array $ids
     * @param array $subordinateIds
     * @return array
     */
    public function subordinateIds(array $ids, array $subordinateIds): array
    {
        // 保存下级编号
        $subordinateIdsOld = $subordinateIds;
        // 重置下级编号
        foreach ($ids as $admin){if(in_array($admin['parent_id'], $subordinateIds)){ $subordinateIds[] = $admin['id']; }}
        // 删除重复下级编号
        $subordinateIds = array_flip(array_flip($subordinateIds));
        // 验证重置前和重置后下级编号是否一致，不一致时重新重置下级编号
        if(count($subordinateIdsOld) !== count($subordinateIds)){ return $this->subordinateIds($ids, $subordinateIds); }
        // 返回下级编号
        return $subordinateIds;
    }
}
