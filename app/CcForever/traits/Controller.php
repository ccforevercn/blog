<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/7/21
 */

namespace App\CcForever\traits;

trait Controller
{
    public abstract function lst(): \Illuminate\Http\JsonResponse; // 列表

    public abstract function count(): \Illuminate\Http\JsonResponse; // 总数

    public abstract function insert(): \Illuminate\Http\JsonResponse; // 添加

    public abstract function update(): \Illuminate\Http\JsonResponse; // 修改

    public abstract function delete(): \Illuminate\Http\JsonResponse; // 删除
}
