<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/8/21
 */

namespace App\Repositories;

use App\Exceptions\{Database, Uploads as ExceptionsUploads };
use App\CcForever\{
    interfaces\Repository as InterfacesRepository,
    service\Check,
    service\HandleArray,
    service\Login,
    traits\Repository as TraitsRepository
};
use App\CcForever\traits\UploadsValidateTrait;
use App\Models\Uploads;

/**
 * 文件上传
 *
 * Class UploadsRepository
 * @package App\Repositories
 */
class UploadsRepository implements InterfacesRepository
{
     use TraitsRepository,UploadsValidateTrait;

    /**
     * 数组处理类
     *
     * @var HandleArray
     */
    private $array;

    /**
     * 登陆处理类
     *
     * @var Login
     */
    private $login;

    public function __construct()
    {
        $this->model = new Uploads();
        $this->login = new Login();
        $this->array = new HandleArray();
    }

    /**
     * 列表
     *
     * @param array $where
     * @param array $order
     * @param int $page
     * @param int $limit
     * @return array
     * @throws ExceptionsUploads
     */
    public function lst(array $where, array $order, int $page, int $limit): array
    {
        // TODO: Implement lst() method.
        $offset = (new Check)->page($page, $limit); // 分页起始值
        // 验证父级编号，如果父级编号不存在则返回所有权限下的管理员
        $adminIds = (new AdminsRepository())->subordinateIds($this->login->tokenId()); // 获取当前管理员编号和下级管理员编号
        // 没有下级
        if(!count($adminIds)) { throw new ExceptionsUploads("暂无列表"); }
        try {
            // 获取列表并返回
            return $this->model->lst(['admin_id' => $adminIds], $order, $offset, $limit);
        }catch (Database $exception){
            throw new ExceptionsUploads($exception->getMessage());
        }
    }

    /**
     * 总数
     *
     * @param array $where
     * @return int
     * @throws ExceptionsUploads
     */
    public function count(array $where): int
    {
        // TODO: Implement count() method.
        // 验证父级编号，如果父级编号不存在则返回所有权限下的管理员
        $adminIds = (new AdminsRepository())->subordinateIds($this->login->tokenId()); // 获取当前管理员编号和下级管理员编号
        // 没有下级
        if(!count($adminIds)) { throw new ExceptionsUploads("暂无列表"); }
        try {
            // 获取列表并返回
            return $this->model->count(['admin_id' => $adminIds]);
        }catch (Database $exception){
            throw new ExceptionsUploads($exception->getMessage());
        }
    }

    /**
     * 添加
     *
     * @param array $data
     * @throws ExceptionsUploads
     */
    public function insert(array $data): void
    {
        // TODO: Implement insert() method.
        // 重置添加数据
        $insert = $this->array->getNotNull($data, 'string', ['file', 'object'], ['path']);
        // 验证必要参数名称是否为空
        $checkKey = $this->array->isKey($insert, 'file', 'path');
        if(!$checkKey){ throw new ExceptionsUploads("请填写必填参数值"); }
        try {
            // 上传文件
            $upload = $this->upload($insert['file'], $insert['path']);
        }catch (ExceptionsUploads $exception){
            throw new ExceptionsUploads($exception->getMessage());
        }
        try{
            $insert = [];
            $insert['path'] = $upload['path'];
            $insert['add_time'] = time();
            $insert['is_del'] = 0;
            $insert['admin_id'] = $this->login->tokenId();
            $this->model->create($insert);
            throw new ExceptionsUploads("上传文件成功", 200, $upload);
        }catch (Database $exception){
            try {
                $this->remove($data['path']);
                throw new ExceptionsUploads("上传文件失败");
            }catch (ExceptionsUploads $exception){
                throw new ExceptionsUploads($exception->getMessage());
            }
        }
    }

    /**
     * 修改
     *
     * @param array $data
     * @param int $id
     * @throws ExceptionsUploads
     */
    public function update(array $data, int $id): void
    {
        // TODO: Implement update() method.
        throw new ExceptionsUploads("禁止修改");
    }

    /**
     * 删除
     *
     * @param int $id
     * @throws ExceptionsUploads
     */
    public function delete(int $id): void
    {
        // TODO: Implement delete() method.
        // 验证编号
        $checkId = $this->model->checkId($this->model->table, $id);
        if(!$checkId){ throw new ExceptionsUploads("已删除，不能重复删除"); }
        // 获取文件路径
        $upload = $this->model->first($this->model->table, $id, ['path']);
        if(!count($upload)) { throw new ExceptionsUploads("已删除，不能重复删除"); }
        try{
            $this->model->update(['is_del' => 1], $id); // 删除
            try {
                $this->remove($upload['path']); // 硬盘删除
            }catch (ExceptionsUploads $exception) {
                throw new ExceptionsUploads($exception->getMessage());
            }
        }catch (Database $exception) {
            throw new ExceptionsUploads($exception->getMessage());
        }
    }
}
