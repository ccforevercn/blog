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
 * 添加验证
 *
 * Class AdminsInsertRequest
 * @package App\Http\Requests\Admins
 */
class AdminsInsertRequest extends Request
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
            'username' => 'bail|required|min:6|max:16',  // 账号
            'password' => 'bail|present|min:8|max:18', // 密码
            'real_name' =>  'bail|required|max:20',  // 管理员昵称
            'status' => 'bail|present|integer|min:0|max:1', // 管理员状态
            'found' => 'bail|present|integer|min:0|max:1', // 创建管理员权限
            'rule_id' =>  'bail|present|integer|min:1', // 规则编号
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
            'username.required' => '请填写账号',
            'username.min' => '账号最少6个字符',
            'username.max' => '账号最多16个字符',
            'password.present' => '参数错误',
            'password.min' => '密码至少是8个字符',
            'password.max' => '密码最多18个字符',
            'real_name.required' => '请填写管理员昵称',
            'real_name.max' => '管理员昵称不能超过20个汉字',
            'status.present' => '参数错误',
            'status.integer' => '管理员状态格式错误',
            'status.min' => '管理员状态错误',
            'status.max' => '管理员状态错误',
            'found.present' => '参数错误',
            'found.integer' => '创建管理员权限格式错误',
            'found.min' => '创建管理员权限状态错误',
            'found.max' => '创建管理员权限状态错误',
            'rule_id.present' => '参数错误',
            'rule_id.integer' => '规则编号格式错误',
            'rule_id.min' => '规则编号错误',
            'email.present' => '参数错误',
            'email.email' => '邮箱格式错误',
        ];
    }
}
