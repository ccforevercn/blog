<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/7/21
 */
namespace App\Http\Requests\Admins;

use App\Http\Requests\Request;

/**
 * 修改验证
 *
 * Class AdminsUpdateRequest
 * @package App\Http\Requests\Admins
 */
class AdminsUpdateRequest extends Request
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
            'id' => 'bail|required|integer|min:1', // 编号
            'password' => 'bail|present', // 密码
            'real_name' =>  'bail|required|max:20',  // 管理员昵称
            'status' => 'bail|nullable|integer|min:0|max:1', // 管理员状态
            'found' => 'bail|nullable|integer|min:0|max:1', // 创建管理员权限
            'rule_id' =>  'bail|nullable|integer|min:1', // 规则编号
            'email' =>  'bail|present|email:rfc,filter', // 管理员邮箱
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
            'id.min' => '参数错误',
            'password.present' => '参数错误',
            'real_name.required' => '请填写管理员昵称',
            'real_name.max' => '管理员昵称不能超过20个汉字',
            'status.integer' => '管理员状态格式错误',
            'status.min' => '管理员状态错误',
            'status.max' => '管理员状态错误',
            'found.integer' => '创建管理员权限格式错误',
            'found.min' => '创建管理员权限状态错误',
            'found.max' => '创建管理员权限状态错误',
            'rule_id.integer' => '规则编号格式错误',
            'rule_id.min' => '规则编号错误',
            'email.present' => '请填写邮箱',
            'email.email' => '邮箱格式错误',
        ];
    }
}
