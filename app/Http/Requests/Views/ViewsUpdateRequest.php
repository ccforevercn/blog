<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/8/4
 */
namespace App\Http\Requests\Views;

use App\Http\Requests\Request;

/**
 * 修改验证
 *
 * Class ViewsUpdateRequest
 * @package App\Http\Requests\Views
 */
class ViewsUpdateRequest extends Request
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
            'id' =>  'bail|required|numeric|min:1',
            'name' =>  'bail|required|max:10',
            'path' =>  'bail|required|max:16|min:1',
            'type' =>  'bail|required|numeric|min:1|max:2',
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
            'id.numeric' => '参数错误',
            'id.min' => '参数错误',
            'name.required' => '请填写视图名称',
            'name.max' => '视图名称不能超过10个汉字',
            'path.required' => '请填写视图地址',
            'path.max' => '视图地址不能超过16个字符',
            'path.min' => '视图地址不能少于1个字符',
            'type.required' => '请选择视图类型',
            'type.numeric' => '视图类型类型错误',
            'type.min' => '视图类型错误，请重新选择',
            'type.max' => '视图类型错误，请重新选择',
        ];
    }
}
