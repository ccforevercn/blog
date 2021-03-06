<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/8/21
 */
namespace App\Http\Requests\Image;

use App\Http\Requests\Request;

/**
 * 添加验证
 *
 * Class ImageRequest
 * @package App\Http\Requests\Image
 */
class ImageRequest extends Request
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
            'name' => 'bail|required|alpha|max:16',
            'path' => 'bail|required|alpha|max:16',
        ];
    }

    /**
     * 重写参数描述
     *
     * @return array
     */
    public function messages()
    {
        return[
            'name.required' => '文件索引不能为空',
            'name.alpha' => '文件索引必须完全由字母构成',
            'name.max' => '文件索引不能超过16个字符',
            'path.required' => '文件存储路径不能为空',
            'path.alpha' => '文件存储路径必须完全由字母构成',
            'path.max' => '文件存储路径不能超过16个字符',
        ];
    }
}
