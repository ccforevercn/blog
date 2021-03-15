<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @date:    2021/3/1
 */

namespace App\CcForever\service\model;

/**
 * 栏目服务类
 *
 * Class Columns
 * @package App\CcForever\service\model
 */
class Columns
{
    /**
     * 导航
     *
     * @param array $columns
     * @param int $pId
     * @param string $prefix
     * @param string $suffix
     * @return array
     */
    public function navigation(array $columns, int $pId, string $prefix, string $suffix): array
    {
        $navigations = []; // 导航列表
        foreach($columns as &$column){
            $navigation = []; //导航
            if($column['parent_id'] == $pId){
                $navigation['id'] = $column['id']; // 编号
                $navigation['name'] = $column['name']; // 名称
                // 外链
                if($column['render']){ $navigation['url'] = $column['page']; }
                // 内链
                else{ $navigation['url'] = $prefix.$column['page'].'/'.$column['id'].$suffix; }
                $navigation['name_alias'] = $column['name_alias']; // 别名
                $navigation['children'] = $this->navigation($columns, $column['id'], $prefix, $suffix); // 子导航
                $navigations[] = $navigation;
            }
        }
        return $navigations;
    }

    /**
     * 顶级栏目
     *
     * @param array $columns
     * @param int $parentId
     * @return array
     */
    public function firstColumn(array $columns, int $parentId): array
    {
        foreach ($columns as &$column){
            // 验证父级编号信息
            if($column['id'] === $parentId){
                // 父级编号为0,返回数据
                if(!$column['parent_id']) { return $column; }
                // 重新查询顶级栏目
                return $this->firstColumn($columns, $column['parent_id']);
            }
        }
        return [];
    }


    /**
     * 格式化栏目子集+
     *
     * @param array $columns
     * @param int $parentId
     * @param array $subsets
     * @return array
     */
    public function subsets(array $columns, int $parentId, array $subsets): array
    {
        foreach ($columns as $column){
            if($parentId == $column['parent_id']){
                $subsets[] = $column;
                $children = $this->subsets($columns, $column['id'], []);
                if(count($children)){ $subsets =  array_merge($subsets , $children); }
            }
        }
        return $subsets;
    }
}
