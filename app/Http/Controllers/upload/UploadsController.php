<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/8/21
 */
namespace App\Http\Controllers\upload;

use App\CcForever\{
    controller\BaseController,
    traits\Controller as TraitsController
};
use App\Exceptions\Uploads as ExceptionsUploads;
use App\Http\Requests\Upload\UploadsRequest;
use App\Repositories\UploadsRepository;

/**
 * 文件上传
 *
 * Class UploadsController
 * @package App\Http\Controllers\upload
 */
class UploadsController extends BaseController
{
    use TraitsController;
    /**
     * @var UploadsRepository
     */
    private $uploads;

    public function __construct()
    {
        parent::__construct();
        $this->uploads = new UploadsRepository();
    }

    /**
     * 列表
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function lst(): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement lst() method.
        try{
            $page = app('request')->input('page', 1);  // 页数
            $limit = app('request')->input('limit', 1);  // 条数
            $list = $this->uploads->lst([], ['add_time'=> 'DESC'], $page, $limit); // 获取列表
            return $this->resources->success("列表", $list); // 返回列表
        }catch (ExceptionsUploads $exception){
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 总数
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function count(): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement count() method.
        try{
            $count = $this->uploads->count([]); // 获取总数
            return $this->resources->success("总数", compact('count')); // 返回总数
        }catch (ExceptionsUploads $exception){
            return $this->resources->error($exception->getMessage());
        }
    }

    /**
     * 添加
     *
     * @param UploadsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function insert(UploadsRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement insert() method.
        try {
            $file = $request->all(); // 获取参数
            // 验证是否有上传文件
            if ($request->hasFile($file['name']) && $request->file($file['name'])->isValid()) {
                // 上传文件并添加到数据库
                $this->uploads->insert(['file' => $request->file($file['name']), 'path' => $file['path']]);
            }
            return $this->resources->error("请上传图片");
        }catch (ExceptionsUploads $exception){
            // 上传成功
            if($exception->getCode() === 200) { return $this->resources->notice($exception->getMessage(), $exception->getUploads()); }
            // 上传失败
            else { return $this->resources->error($exception->getMessage()); }
        }
    }

    /**
     * 修改
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(): \Illuminate\Http\JsonResponse
    {
        // TODO: Implement update() method.
        try {
            $this->uploads->update([], 0); // 修改
            return $this->resources->notice("修改成功");
        }catch (ExceptionsUploads $exception){
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
        try {
            $id = (int)app('request')->input('id');
            $this->uploads->delete($id);
            return $this->resources->notice('删除成功');
        }catch (ExceptionsUploads $exception){
            return $this->resources->error($exception->getMessage());
        }
    }
}
