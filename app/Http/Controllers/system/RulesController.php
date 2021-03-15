<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/7/23
 */

namespace App\Http\Controllers\system;

use App\Exceptions\Rules as ExceptionsRules;
use App\CcForever\{
    controller\BaseController,
    traits\Controller as TraitsController
};
use App\Http\Requests\Rules\{
    RulesInsertRequest,
    RulesListRequest,
    RulesUpdateRequest,
};
use App\Repositories\RulesRepository;

/**
 * 规则
 *
 * Class RulesController
 * @package App\Http\Controllers\system
 */
class RulesController extends BaseController
{
    use TraitsController;

    /**
     * @var RulesRepository
     */
    private $rules;

    public function __construct()
    {
        parent::__construct();
        $this->rules = new RulesRepository();
    }

    /**
     * 列表
     *
     * @param RulesListRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function lst(RulesListRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement lst() method.
        try{
            $where = $request->all(); // 请求参数
            $list = $this->rules->lst($where, ['id' => 'DESC'], $where['page'], $where['limit']); // 列表
            return $this->resources->success("列表", $list); // 返回列表
        }catch (ExceptionsRules $exception) {
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 总数
     *
     * @param RulesListRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function count(RulesListRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement count() method.
        try{
            $where = $request->all(); // 请求参数
            $count = $this->rules->count($where); // 总数
            return $this->resources->success("总数", compact('count')); // 返回总数
        }catch (ExceptionsRules $exception) {
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 添加
     *
     * @param RulesInsertRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function insert(RulesInsertRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement insert() method.
        try{
            $data = $request->all(); // 添加参数
            $this->rules->insert($data); // 添加
            return $this->resources->notice("添加成功"); // 返回通知
        }catch (ExceptionsRules $exception) {
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 修改
     *
     * @param RulesUpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(RulesUpdateRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement update() method.
        try{
            $data = $request->all(); // 修改参数
            $this->rules->update($data, $data['id']); // 修改数据
            return $this->resources->notice("修改成功"); // 返回通知
        }catch (ExceptionsRules $exception){
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 删除
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement delete() method.
        try{
            $id = (int)app('request')->input('id', 0);
            $this->rules->delete($id);
            return $this->resources->notice("删除成功");
        }catch (ExceptionsRules $exception) {
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 规则菜单
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function menu(): \Illuminate\Http\JsonResponse
    {
        try{
            $id = (int)app('request')->input('id', 0);
            $rulesMenu = $this->rules->rulesMenu($id);
            return $this->resources->success("规则菜单", $rulesMenu);
        }catch (ExceptionsRules $exception) {
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 所有菜单(当前管理员可看到的)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function menus(): \Illuminate\Http\JsonResponse
    {
        $menus = $this->rules->menus();
        return $this->resources->success("菜单", $menus);
    }

    /**
     * 所有规则(当前管理员可看到的)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function rules(): \Illuminate\Http\JsonResponse
    {
        try{
            $rules = $this->rules->rules();
            return $this->resources->success("规则", $rules);
        }catch (ExceptionsRules $exception) {
            return $this->resources->error($exception->getMessage());
        }
    }
}
