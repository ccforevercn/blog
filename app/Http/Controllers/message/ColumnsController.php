<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/7/31
 */

namespace App\Http\Controllers\message;

use App\CcForever\{
    controller\BaseController,
    traits\Controller as TraitsController
};
use App\Exceptions\Columns as ExceptionsColumns;
use App\Http\Requests\Columns\{
    ColumnsContentRequest,
    ColumnsInsertRequest,
    ColumnsListRequest,
    ColumnsUpdateRequest,
};
use App\Repositories\ColumnsRepository;

/**
 * 栏目
 *
 * Class ColumnsController
 * @package App\Http\Controllers\message
 */
class ColumnsController extends BaseController
{
    use TraitsController;

    /**
     * @var ColumnsRepository
     */
    private $columns;

    public function __construct()
    {
        parent::__construct();
        $this->columns = new ColumnsRepository();
    }

    /**
     * 列表
     *
     * @param ColumnsListRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function lst(ColumnsListRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement lst() method.
        try{
            $where = $request->all(); // 参数
            $list = $this->columns->lst($where, [], $where['page'], $where['limit']); // 获取列表
            return $this->resources->success("列表", $list); // 返回列表
        }catch (ExceptionsColumns $exception) {
            // 返回异常抛出的错误
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 总数
     *
     * @param ColumnsListRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function count(ColumnsListRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement count() method.
        try{
            $where = $request->all(); // 参数
            $count = $this->columns->count($where); // 获取总数
            return $this->resources->success("总数", compact('count')); // 返回总数
        }catch (ExceptionsColumns $exception) {
            // 返回异常抛出的错误
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 添加
     *
     * @param ColumnsInsertRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function insert(ColumnsInsertRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement insert() method.
        try{
            $insert = $request->all(); // 参数
            $this->columns->insert($insert); // 添加
            return $this->resources->notice("添加成功");
        }catch (ExceptionsColumns $exception) {
            // 返回异常抛出的错误
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 修改
     *
     * @param ColumnsUpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ColumnsUpdateRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement update() method.
        try{
            $update = $request->all(); // 参数
            $this->columns->update($update, $update['id']); // 修改
            return $this->resources->notice("修改成功");
        }catch (ExceptionsColumns $exception) {
            // 返回异常抛出的错误
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
            $id = (int)app('request')->input('id');
            $this->columns->delete($id); // 删除
            return $this->resources->notice("删除成功");
        }catch (ExceptionsColumns $exception) {
            // 返回异常抛出的错误
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 内容
     *
     * @param ColumnsContentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function content(ColumnsContentRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $request->all(); // 参数
            $content = $this->columns->content($data, $data['id'], $data['type']); // 内容处理
            if($data['type']) { return $this->resources->success("内容", $content); }
            return $this->resources->notice("添加/修改成功", $content);
        }catch (ExceptionsColumns $exception) {
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 栏目
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function columns(): \Illuminate\Http\JsonResponse
    {
        // 获取栏目
        $columns = $this->columns->total(['id', 'name'], [], [], 0, 0);
        // 返回栏目
        return $this->resources->success("栏目", $columns);
    }
}
