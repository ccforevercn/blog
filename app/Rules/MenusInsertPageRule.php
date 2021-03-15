<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day: 2020/8/21
 */
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * 菜单添加页面规则
 *
 * Class MenusInsertPageRule
 * @package App\Rules
 */
class MenusInsertPageRule implements Rule
{
    /**
     * 验证数据
     *
     * @var string
     */
    private $value = '';

    /**
     * 存在页面
     *
     * @var array
     */
    private $page = ['/administrators'];
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if(in_array($value, $this->page)){
            $this->value = $value;
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->value.'已存在，请重新添加页面链接';
    }
}
