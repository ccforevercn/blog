<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/8/4
 */

namespace App\Repositories;

use App\Models\Views;
use App\CcForever\{interfaces\Repository as InterfacesRepository,
    service\Check,
    service\HandleArray,
    traits\Repository as TraitsRepository};
use App\Exceptions\{
    Views as ExceptionsViews, Database
};

/**
 * 视图
 *
 * Class ViewsRepository
 * @package App\Repositories
 */
class ViewsRepository implements InterfacesRepository
{
    use TraitsRepository;

    /**
     * 数组处理类
     *
     * @var HandleArray
     */
    private $array;

    public function __construct()
    {
        $this->model = new Views();
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
     * @throws ExceptionsViews
     */
    public function lst(array $where, array $order, int $page, int $limit): array
    {
        // TODO: Implement lst() method.
        $offset = (new Check)->page($page, $limit); // 分页起始值
        $wheres = $this->array->getNotNull($where, 'int', ['type']); // 重置条件
        try {
            // 获取列表并返回
            return $this->model->lst($wheres, $order, $offset, $limit);
        }catch (Database $exception){
            throw new ExceptionsViews($exception->getMessage());
        }
    }

    /**
     * 总数
     *
     * @param array $where
     * @return int
     * @throws ExceptionsViews
     */
    public function count(array $where): int
    {
        // TODO: Implement count() method.
        $wheres = $this->array->getNotNull($where, 'int', ['type']); // 重置条件
        try {
            // 获取总数并返回
            return $this->model->count($wheres);
        }catch (Database $exception){
            throw new ExceptionsViews($exception->getMessage());
        }
    }

    /**
     * 添加
     *
     * @param array $data
     * @throws ExceptionsViews
     */
    public function insert(array $data): void
    {
        // TODO: Implement insert() method.
        // 重置添加数据
        $insert = $this->array->getNotNull($data, 'string', ['name'], ['path'], ['type', 'int']);
        // 验证必要参数名称是否为空
        $checkKey = $this->array->isKey($insert, 'name', 'path');
        if(!$checkKey){ throw new ExceptionsViews("请填写必填参数值"); }
        // 验证地址是否重复
        $checkPath = $this->model->get($this->model->table, ['path'], [], ['path' => $insert['path']], [], ['offset' => 0, 'limit' => 1]);
        if(count($checkPath)) { throw new ExceptionsViews("path已存在，不能重复添加"); }
        $insert['is_del'] = 0;
        $insert['add_time'] = time();
        try {
            $this->model->create($insert);
        }catch (Database $exception){
            throw new ExceptionsViews($exception->getMessage());
        }

    }

    /**
     * 修改
     *
     * @param array $data
     * @param int $id
     * @throws ExceptionsViews
     */
    public function update(array $data, int $id): void
    {
        // TODO: Implement update() method.
        // 验证编号
        $checkId = $this->model->checkId($this->model->table, $id);
        if(!$checkId){ throw new ExceptionsViews("参数错误"); }
        // 重置修改数据
        $update = $this->array->getNotNull($data, 'string', ['name'], ['path'], ['type', 'int']);
        // 验证必要参数名称、地址是否为空
        $checkKey = $this->array->isKey($update, 'name', 'path');
        if(!$checkKey){ throw new ExceptionsViews("请填写必填参数值"); }
        // 验证地址是否重复
        $checkPath = $this->model->get($this->model->table, ['id', 'path'], [], ['path' => $update['path']], [], ['offset' => 0, 'limit' => 2]);
        switch (count($checkPath)) {
            case 0:break;
            case 1:if($checkPath[0]['id'] !== $id) { throw new ExceptionsViews("地址已存在，不能重复添加"); } break;
            default: throw new ExceptionsViews("地址已存在，不能重复添加");
        }
        // 验证修改数据和数据库数据是否一致
        $checkUpdate = $this->model->first($this->model->table, $id, array_keys($update)) === $update;
        if($checkUpdate) { return; }
        try{
            // 修改数据
            $this->model->update($update, $id);
        }catch (Database $exception) {
            throw new ExceptionsViews($exception->getMessage());
        }
    }

    /**
     * 删除
     *
     * @param int $id
     * @throws ExceptionsViews
     */
    public function delete(int $id): void
    {
        // TODO: Implement delete() method.
        $checkId = $this->model->checkId($this->model->table, $id);
        if(!$checkId){ throw new ExceptionsViews("已删除，不能重复删除"); }
        try{
            $this->model->update(['is_del' => 1], $id); // 删除
        }catch (Database $exception) {
            throw new ExceptionsViews($exception->getMessage());
        }
    }

    /**
     * 视图
     *
     * @param int $type
     * @return array
     */
    public function total(int $type): array
    {
        return $this->model->get($this->model->table, ['name', 'path'], [], ['type' => $type], ['add_time' => 'DESC']);
    }

}
