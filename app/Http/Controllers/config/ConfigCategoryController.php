<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/8/4
 */

namespace App\Http\Controllers\config;

use App\Exceptions\ConfigCategory as ExceptionsConfigCategory;
use App\CcForever\{
    controller\BaseController,
    traits\Controller as TraitsController
};
use App\Http\Requests\ConfigCategory\{
    ConfigCategoryInsertRequest,
    ConfigCategoryListRequest,
    ConfigCategoryUpdateRequest
};
use App\Repositories\ConfigCategoryRepository;

/**
 * 配置分类
 *
 * Class ConfigCategoryController
 * @package App\Http\Controllers\config
 */
class ConfigCategoryController extends BaseController
{
    use TraitsController;

    /**
     * @var ConfigCategoryRepository
     */
    private $configCategory;

    public function __construct()
    {
        parent::__construct();
        $this->configCategory = new ConfigCategoryRepository();
    }

    /**
     * 列表
     *
     * @param ConfigCategoryListRequest $request
     * @return object
     */
    public function lst(ConfigCategoryListRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement lst() method.
        try {
            $where = $request->all(); // 条件
            $list = $this->configCategory->lst($where, ['id' => 'ASC'], $where['page'], $where['limit']); // 获取列表
            return $this->resources->success("列表", $list); // 返回列表
        }catch (ExceptionsConfigCategory $exception) {
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 总数
     *
     * @param ConfigCategoryListRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function count(ConfigCategoryListRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement count() method.
        try {
            $where = $request->all(); // 条件
            $count = $this->configCategory->count($where); // 获取总数
            return $this->resources->success("总数", compact('count')); // 返回总数
        }catch (ExceptionsConfigCategory $exception) {
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 添加
     *
     * @param ConfigCategoryInsertRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function insert(ConfigCategoryInsertRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement insert() method.
        try {
            $data = $request->all(); // 参数
            $this->configCategory->insert($data); // 添加
            return $this->resources->notice("添加成功"); // 返回
        }catch (ExceptionsConfigCategory $exception) {
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 修改
     *
     * @param ConfigCategoryUpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ConfigCategoryUpdateRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement update() method.
        try {
            $data = $request->all(); // 参数
            $this->configCategory->update($data, $data['id']); // 修改
            return $this->resources->notice("修改成功"); // 返回
        }catch (ExceptionsConfigCategory $exception) {
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
        try {
            $id = (int)app('request')->input('id', 0);
            $this->configCategory->delete($id); // 删除
            return $this->resources->notice("删除成功"); // 返回
        }catch (ExceptionsConfigCategory $exception) {
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 配置分类
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function category(): \Illuminate\Http\JsonResponse
    {
        $configCategory = $this->configCategory->total(); // 配置分类
        return $this->resources->success("配置分类", $configCategory); // 返回
    }
}
