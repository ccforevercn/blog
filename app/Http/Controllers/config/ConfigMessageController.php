<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/8/4
 */

namespace App\Http\Controllers\config;

use App\Exceptions\ConfigMessage as ExceptionsConfigMessage;
use App\CcForever\{
    controller\BaseController,
    traits\Controller as TraitsController
};
use App\Http\Requests\ConfigMessage\{
    ConfigMessageInsertRequest,
    ConfigMessageListRequest,
    ConfigMessageUpdateRequest
};
use App\Repositories\ConfigMessageRepository;

/**
 * 配置信息
 *
 * Class ConfigMessageController
 * @package App\Http\Controllers\config
 */
class ConfigMessageController extends BaseController
{
    use TraitsController;

    /**
     * @var ConfigMessageRepository
     */
    private $configMessage;

    public function __construct()
    {
        parent::__construct();
        $this->configMessage = new ConfigMessageRepository();
    }

    /**
     * 列表
     *
     * @param ConfigMessageListRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function lst(ConfigMessageListRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement lst() method.
        try {
            $where = $request->all(); // 条件
            $list = $this->configMessage->lst($where, ['id' => 'ASC'], $where['page'], $where['limit']); // 获取列表
            return $this->resources->success("列表", $list); // 返回列表
        }catch (ExceptionsConfigMessage $exception) {
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 总数
     *
     * @param ConfigMessageListRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function count(ConfigMessageListRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement count() method.
        try {
            $where = $request->all(); // 条件
            $count = $this->configMessage->count($where); // 获取总数
            return $this->resources->success("总数", compact('count')); // 返回总数
        }catch (ExceptionsConfigMessage $exception) {
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 添加
     *
     * @param ConfigMessageInsertRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function insert(ConfigMessageInsertRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement insert() method.
        try {
            $data = $request->all(); // 参数
            $this->configMessage->insert($data); // 添加
            return $this->resources->notice("添加成功"); // 返回
        }catch (ExceptionsConfigMessage $exception) {
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 修改
     *
     * @param ConfigMessageUpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ConfigMessageUpdateRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement update() method.
        try {
            $data = $request->all(); // 参数
            $this->configMessage->update($data, $data['id']); // 修改
            return $this->resources->notice("修改成功"); // 返回
        }catch (ExceptionsConfigMessage $exception) {
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
            $this->configMessage->delete($id); // 删除
            return $this->resources->notice("删除成功"); // 返回
        }catch (ExceptionsConfigMessage $exception) {
            return $this->resources->error($exception->getMessage());
        }
    }
}
