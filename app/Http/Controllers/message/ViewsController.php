<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/8/4
 */

namespace App\Http\Controllers\message;

use App\Exceptions\Views as ExceptionsViews;
use App\CcForever\{
    controller\BaseController,
    traits\Controller as TraitsController
};
use App\Http\Requests\Views\{
    ViewsInsertRequest,
    ViewsListRequest,
    ViewsUpdateRequest,
};
use App\Repositories\ViewsRepository;

/**
 * 视图
 *
 * Class ViewsController
 * @package App\Http\Controllers\message
 */
class ViewsController extends BaseController
{
    use TraitsController;

    /**
     * @var ViewsRepository
     */
    private $views;

    public function __construct()
    {
        parent::__construct();
        $this->views = new ViewsRepository();
    }

    /**
     * 列表
     *
     * @param ViewsListRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function lst(ViewsListRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement lst() method.
        try{
            $where = $request->all();  // 查询条件
            $list = $this->views->lst($where, ['add_time'=> 'DESC'], $where['page'], $where['limit']); // 获取列表
            return $this->resources->success("列表", $list); // 返回列表
        }catch (ExceptionsViews $exception){
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 总数
     *
     * @param ViewsListRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function count(ViewsListRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement count() method.
        try{
            $where = $request->all();  // 查询条件
            $count =  $this->views->count($where); // 获取总数
            return $this->resources->success("总数", compact('count')); // 返回总数
        }catch (ExceptionsViews $exception){
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 添加
     *
     * @param ViewsInsertRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function insert(ViewsInsertRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement insert() method.
        try {
            $data = $request->all();
            $this->views->insert($data);
            return $this->resources->notice('添加成功');
        }catch (ExceptionsViews $exception){
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 修改
     *
     * @param ViewsUpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ViewsUpdateRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement update() method.
        try {
            $data = $request->all(); // 修改参数
            $this->views->update($data, (int)$data['id']); // 修改
            return $this->resources->notice('修改成功'); // 返回
        }catch (ExceptionsViews $exception){
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
            $this->views->delete($id);
            return $this->resources->notice('删除成功');
        }catch (ExceptionsViews $exception){
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 视图
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function views(): \Illuminate\Http\JsonResponse
    {
        $type = (int)app('request')->input('type', 0);
        $views = $this->views->total($type);
        return $this->resources->success('视图', $views);
    }
}
