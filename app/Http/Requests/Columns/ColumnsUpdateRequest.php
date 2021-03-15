<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/7/31
 */

namespace App\Http\Requests\Columns;

use App\Http\Requests\Request;

/**
 * 修改验证
 *
 * Class ColumnsUpdateRequest
 * @package App\Http\Requests\Columns
 */
class ColumnsUpdateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'bail|required|integer|min:1',
            'name' =>  'bail|required|max:80',
            'name_alias' =>  'bail|nullable|max:80',
            'parent_id' => 'bail|required|integer|min:0',
            'image' => 'bail|nullable|max:128',
            'banner_image' => 'bail|nullable|max:128',
            'keywords' =>  'bail|nullable|max:100',
            'description' =>  'bail|nullable|max:200',
            'weight' =>  'bail|nullable|integer|min:0|max:99999',
            'limit' =>  'bail|nullable|integer|min:0|max:100',
            'sort' =>  'bail|nullable|integer|min:0|max:6',
            'navigation' =>  'bail|nullable|integer|min:0|max:1',
            'render' =>  'bail|required|integer|min:0|max:1',
            'page' =>  'bail|required|max:100',
        ];
    }

    /**
     * 重写参数描述
     * @return array
     */
    public function messages()
    {
        return [
            'id.required' => '参数错误',
            'id.integer' => '参数错误',
            'id.min' => '参数错误',
            'name.required' => '请填写栏目名称',
            'name.max' => '栏目名称不能超过80个汉字',
            'name_alias.max' => '栏目别名不能超过80个汉字',
            'parent_id.required' => '请选择上级栏目',
            'parent_id.integer' => '上级栏目类型错误',
            'parent_id.min' => '上级栏目错误',
            'image.max' => '栏目图片不能超过128位',
            'banner_image.max' => '栏目轮播图片路径不能超过128位',
            'keywords.max' => '栏目关键字不能超过100个汉字',
            'description.max' => '栏目描述不能超过200个汉字',
            'weight.integer' => '权重类型错误',
            'weight.min' => '权重不能小于0',
            'weight.max' => '权重不能大于99999',
            'limit.integer' => '信息每页条数错误',
            'limit.min' => '信息每页条数错误，请重新选择',
            'limit.max' => '信息每页条数错误，请重新选择',
            'sort.integer' => '排序类型错误',
            'sort.min' => '排序错误，请重新选择',
            'sort.max' => '排序错误，请重新选择',
            'navigation.integer' => '导航状态类型错误',
            'navigation.min' => '导航状态错误，请重新选择',
            'navigation.max' => '导航状态错误，请重新选择',
            'render.required' => '请选择渲染类型',
            'render.integer' => '渲染类型类型错误',
            'render.min' => '渲染类型错误，请重新选择',
            'render.max' => '渲染类型错误，请重新选择',
            'page.required' => '请选择栏目页面/链接',
            'page.max' => '栏目页面/链接不能超过100个字符',
        ];
    }
}
