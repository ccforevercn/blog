<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2021/2/23
 */

namespace App\CcForever\service\model;

/**
 * 菜单
 *
 * Class Menus
 * @package App\CcForever\service\model
 */
class Menus
{
    /**
     * 侧边栏
     *
     * @param array $menus
     * @param int $parentId
     * @return array
     */
    public function sidebar(array $menus, int $parentId): array
    {
        $sidebars = [];
        foreach($menus as &$menu){
            $sidebar = [];
            if($menu['parent_id'] === $parentId){
                $sidebar['top'] = $menu['parent_id'] === 0 ?? false;
                $sidebar['page'] = $menu['page'];
                $sidebar['name'] = substr($menu['page'], 1, strlen($menu['page']));
                $sidebar['meta']['title'] = $menu['name'];
                $sidebar['meta']['icon'] = $menu['icon'];
                $sidebar['children'] = $this->sidebar($menus, $menu['id']);
                if(count($sidebar['children']) || !$sidebar['top']){
                    // 子菜单存储或者当前菜单不是顶级菜单
                    $sidebars[] = $sidebar;
                }
            }
        }
        return $sidebars;
    }

    /**
     * 父级插入
     *
     * @param array $total
     * @param int $id
     * @param array $parentIds
     * @return array
     */
    public function addMenusParentIds(array $total, int $id, array $parentIds): array
    {
        foreach ($total as $item){
            if($item['id'] === $id){
                // 父级存在
                if($item['parent_id']){
                    // 验证父级不存在时，push进去
                    if(!in_array($item['parent_id'], $parentIds)){ array_push($parentIds, $item['parent_id']); }
                    return $this->addMenusParentIds($total, $item['parent_id'], $parentIds);
                }
            }
        }
        return $parentIds;
    }

    /**
     * 规则菜单
     *
     * @param array $menus
     * @param array $total
     * @param int $parentId
     * @return array
     */
    public function rules(array $menus, array $total,int $parentId): array
    {
        $loop = 0; // 循环次数
        $menusChildren = []; // 重置子菜单
        foreach ($total as &$item){
            if($item['parent_id'] === $parentId){
                $menus[$loop]['id'] = $item['id'];
                $menus[$loop]['label'] = $item['name'];
                $menus[$loop]['children'] = $this->rules($menusChildren, $total, $item['id']);
                // 验证子菜单是否存在
                if(!count($menus[$loop]['children'])){ unset($menus[$loop]['children']); }
                $loop++; // 累计次数
            }
        }
        return $menus;
    }
}
