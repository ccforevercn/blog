<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/8/3
 */

namespace App\Http\Controllers\message;

use App\Exceptions\Tags as ExceptionsTags;
use App\CcForever\{
    controller\BaseController,
    traits\Controller as TraitsController
};
use App\Http\Requests\Tags\{
    TagsInsertRequest,
    TagsListRequest,
    TagsUpdateRequest
};
use App\Repositories\TagsRepository;

/**
 * 标签
 *
 * Class TagsController
 * @package App\Http\Controllers\message
 */
class TagsController extends  BaseController
{
    use TraitsController;

    /**
     * @var TagsRepository
     */
    private $tags;

    public function __construct()
    {
        parent::__construct();
        $this->tags = new TagsRepository();
    }

    /**
     * 列表
     *
     * @param TagsListRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function lst(TagsListRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement lst() method.
        try{
            $where = $request->all(); // 查询条件
            $list =  $this->tags->lst($where, ['add_time'=> 'DESC'], $where['page'], $where['limit']); // 获取列表
            return $this->resources->success("列表", $list); // 返回列表
        }catch (ExceptionsTags $exception){
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 总数
     *
     * @param TagsListRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function count(TagsListRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement count() method.
        try{
            $where = $request->all(); // 查询条件
            $count =  $this->tags->count($where); // 获取总数
            return $this->resources->success("总数", compact('count')); // 返回总数
        }catch (ExceptionsTags $exception){
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 添加
     *
     * @param TagsInsertRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function insert(TagsInsertRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement insert() method.
        try {
            $data = $request->all(); // 添加参数
            $this->tags->insert($data); // 添加
            return $this->resources->notice('添加成功'); // 返回
        }catch (ExceptionsTags $exception){
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 修改
     *
     * @param TagsUpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(TagsUpdateRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement update() method.
        try {
            $data = $request->all(); // 修改参数
            $this->tags->update($data, (int)$data['id']); // 修改
            return $this->resources->notice('修改成功'); // 返回
        }catch (ExceptionsTags $exception){
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
            $id = (int)app('request')->input('id');
            $this->tags->delete($id);
            return $this->resources->notice('删除成功');
        }catch (ExceptionsTags $exception){
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 标签
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function tags(): \Illuminate\Http\JsonResponse
    {
        $tags = $this->tags->total();
        return $this->resources->success('标签', $tags);
    }

}
