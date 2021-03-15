<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/8/14
 */

namespace App\Http\Controllers\seo;

use App\Exceptions\Links as ExceptionsLinks;
use App\CcForever\{
    controller\BaseController,
    traits\Controller as TraitsController
};
use App\Http\Requests\Links\{
    LinksInsertRequest,
    LinksListRequest,
    LinksUpdateRequest,
};
use App\Repositories\LinksRepository;

/**
 * 友情链接
 *
 * Class LinksController
 * @package App\Http\Controllers\seo
 */
class LinksController extends BaseController
{
    use TraitsController;

    /**
     * @var LinksRepository
     */
    private $links;

    public function __construct()
    {
        parent::__construct();
        $this->links = new LinksRepository();
    }

    /**
     * 列表
     *
     * @param LinksListRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function lst(LinksListRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement lst() method.
        try {
            $where = $request->all(); // 条件
            $list = $this->links->lst($where, [], $where['page'], $where['limit']); // 获取列表
            return $this->resources->success("列表", $list); // 返回列表
        }catch (ExceptionsLinks $exception) {
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 总数
     *
     * @param LinksListRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function count(LinksListRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement count() method.
        try {
            $where = $request->all(); // 条件
            $count = $this->links->count($where); // 获取总数
            return $this->resources->success("总数", compact('count')); // 返回总数
        }catch (ExceptionsLinks $exception) {
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 添加
     *
     * @param LinksInsertRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function insert(LinksInsertRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement insert() method.
        try {
            $data = $request->all(); // 参数
            $this->links->insert($data); // 添加
            return $this->resources->notice("添加成功"); // 返回
        }catch (ExceptionsLinks $exception) {
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 修改
     *
     * @param LinksUpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(LinksUpdateRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement update() method.
        try {
            $data = $request->all(); // 参数
            $this->links->update($data, $data['id']); // 修改
            return $this->resources->notice("修改成功"); // 返回
        }catch (ExceptionsLinks $exception) {
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
            $this->links->delete($id); // 删除
            return $this->resources->notice("删除成功"); // 返回
        }catch (ExceptionsLinks $exception) {
            return $this->resources->error($exception->getMessage());
        }
    }
}
