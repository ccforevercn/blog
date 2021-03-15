<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/8/5
 */
namespace App\Http\Requests\Banners;

use App\Http\Requests\Request;

/**
 * 修改验证
 *
 * Class BannersUpdateRequest
 * @package App\Http\Requests\Banners
 */
class BannersUpdateRequest extends Request
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
            'name' =>  'bail|required|max:30',
            'link' =>  'bail|present',
            'image' =>  'bail|required|max:128',
            'weight' =>  'bail|required|integer|min:1',
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
            'name.required' => '请填写名称',
            'name.max' => '名称不能超过20个汉字',
            'link.present' => '参数错误',
            'image.required' => '请选择图片',
            'image.max' => '图片地址不能超过128个字符',
            'weight.required' => '请输入权重',
            'weight.integer' => '权重类型错误',
            'weight.min' => '权重错误，请重新输入',
        ];
    }
}
