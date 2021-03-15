<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2021/2/22
 */

namespace App\CcForever\service;

/**
 * 验证类
 *
 * Class Check
 * @package App\CcForever\service
 */
class Check
{
    /**
     * 密码加密
     *
     * @param string $password
     * @param string $suffix
     * @return string
     */
    public function password(string $password, string $suffix = ''): string
    {
        return md5(md5($password).$suffix);
    }

    /**
     * 分页
     *
     * @param int $page
     * @param int $limit
     * @return int
     */
    public function page(int $page, int $limit): int
    {
        $page = $page < 1 ? 1: $page;
        $page = bcsub($page, 1, 0);
        $offset = (int)bcmul($page, $limit, 0);
        return $offset;
    }

    /**
     * 信息排序
     *
     * @param int $type
     * @return array
     */
    public function messageOrderBy(int $type): array
    {
        switch ($type) {
            case 1: // 修改时间升序
                $select = 'update_time';
                $value = 'ASC';
                break;
            case 2: // 修改时间倒叙
                $select = 'update_time';
                $value = 'DESC';
                break;
            case 3: // 权重升序
                $select = 'weight';
                $value = 'ASC';
                break;
            case 4: // 权重倒叙
                $select = 'weight';
                $value = 'DESC';
                break;
            case 5: // 点击量升序
                $select = 'click';
                $value = 'ASC';
                break;
            case 6: // 点击量降序
                $select = 'click';
                $value = 'DESC';
                break;
            default: // 默认编号倒叙
                $select = 'id';
                $value = 'DESC';
        }
        return [$select => $value];
    }

    /**
     * 上一条信息
     *
     * @param int $type
     * @return array
     */
    public function whereMessagePro(int $type): array
    {
        switch ($type){
            case 1: // 修改时间升序
                $select = 'update_time';
                $value = 'DESC';
                $condition = '<=';
                break;
            case 2: // 修改时间倒叙
                $select = 'update_time';
                $value = 'ASC';
                $condition = '>=';
                break;
            case 3: // 权重升序
                $select = 'weight';
                $value = 'DESC';
                $condition = '<=';
                break;
            case 4: // 权重倒叙
                $select = 'weight';
                $value = 'ASC';
                $condition = '>=';
                break;
            case 5: // 点击量升序
                $select = 'click';
                $value = 'DESC';
                $condition = '<=';
                break;
            case 6: // 点击量降序
                $select = 'click';
                $value = 'ASC';
                $condition = '>=';
                break;
            default: // 默认编号倒叙
                $select = 'id';
                $value = 'ASC';
                $condition = '>';
                break;
        }
        return compact('select', 'value', 'condition');
    }

    /**
     * 下一条信息
     *
     * @param int $type
     * @return array
     */
    public function whereMessageNext(int $type): array
    {
        switch ($type){
            case 1: // 修改时间升序
                $select = 'update_time';
                $value = 'ASC';
                $condition = '>=';
                break;
            case 2: // 修改时间倒叙
                $select = 'update_time';
                $value = 'DESC';
                $condition = '<=';
                break;
            case 3: // 权重升序
                $select = 'weight';
                $value = 'ASC';
                $condition = '>=';
                break;
            case 4: // 权重倒叙
                $select = 'weight';
                $value = 'DESC';
                $condition = '<=';
                break;
            case 5: // 点击量升序
                $select = 'click';
                $value = 'ASC';
                $condition = '>=';
                break;
            case 6: // 点击量降序
                $select = 'click';
                $value = 'DESC';
                $condition = '<=';
                break;
            default: // 默认编号倒叙
                $select = 'id';
                $value = 'DESC';
                $condition = '<';
                break;
        }
        return compact('select', 'value', 'condition');
    }
}
