<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/8/3
 */
namespace App\Http\Controllers\message;

use App\CcForever\{
    controller\BaseController,
    traits\Controller as TraitsController
};
use App\Exceptions\Messages as ExceptionsMessages;
use App\Http\Requests\Messages\{
    MessagesContentRequest,
    MessagesInsertRequest,
    MessagesListRequest,
    MessagesUpdateRequest,
};
use App\Repositories\MessagesRepository;

/**
 * 信息
 *
 * Class MessagesController
 * @package App\Http\Controllers\message
 */
class MessagesController extends BaseController
{
    use TraitsController;

    /**
     * @var MessagesRepository
     */
    private $messages;

    public function __construct()
    {
        parent::__construct();
        $this->messages = new MessagesRepository();
    }

    /**
     * 列表
     *
     * @param MessagesListRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function lst(MessagesListRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement lst() method.
        try{
            $where = $request->all();
            $list =  $this->messages->lst($where, ['update_time'=> 'DESC'], $where['page'], $where['limit']); // 获取列表
            return $this->resources->success("列表", $list); // 返回列表
        }catch (ExceptionsMessages $exception){
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 总数
     *
     * @param MessagesListRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function count(MessagesListRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement count() method.
        try{
            $where = $request->all(); // 条件
            $count =  $this->messages->count($where); // 获取总数
            return $this->resources->success("总数", compact('count')); // 返回总数
        }catch (ExceptionsMessages $exception){
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 添加
     *
     * @param MessagesInsertRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function insert(MessagesInsertRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement insert() method.
        try {
            $data = $request->all(); // 添加数据
            $this->messages->insert($data); // 插入数据
            return $this->resources->notice('添加成功'); // 返回状态
        }catch (ExceptionsMessages $exception){
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 修改
     *
     * @param MessagesUpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(MessagesUpdateRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement update() method.
        try {
            $data = $request->all(); // 修改参数
            $this->messages->update($data, (int)$data['id']); // 修改数据
            return $this->resources->notice('修改成功'); // 返回状态
        }catch (ExceptionsMessages $exception){
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
            $id = (int)app('request')->input('id'); // 编号
            $this->messages->delete($id); // 删除
            return $this->resources->notice('删除成功'); // 返回状态
        }catch (ExceptionsMessages $exception){
            return $this->resources->error($exception->getMessage());
        }
    }


    /**
     * 内容 添加/修改/查询
     *
     * @param MessagesContentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function content(MessagesContentRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $request->all(); // 参数
            $content = $this->messages->content($data, $data['id'], $data['type']); // 内容处理
            if($data['type']) { return $this->resources->success("内容", $content); }
            return $this->resources->notice("添加/修改成功", $content);
        }catch (ExceptionsMessages $exception) {
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
        try {
            $id = (int)app('request')->input('id', 0); // 编号
            $tags = $this->messages->tags($id); // 标签列表
            return  $this->resources->success("标签", $tags); // 返回
        }catch (ExceptionsMessages $exception) {
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 点击量/权重修改
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function number(): \Illuminate\Http\JsonResponse
    {
        try {
            $id = (int)app('request')->input('id', 0);
            $type = (string)app('request')->input('type', 0);
            $value = (int)app('request')->input('value', 0);
            $this->messages->number($id, $type, $value);
            return  $this->resources->notice("修改成功");
        }catch (ExceptionsMessages $exception) {
            return $this->resources->error($exception->getMessage());
        }
    }
}
