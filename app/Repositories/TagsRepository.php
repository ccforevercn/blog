<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/8/3
 */
namespace App\Repositories;

use App\CcForever\{
    interfaces\Repository as InterfacesRepository,
    service\Check,
    service\HandleArray,
    traits\Repository as TraitsRepository
};
use App\Models\Tags;
use App\Exceptions\{
    Tags as ExceptionsTags, Database
};

/**
 * 标签
 *
 * Class TagsRepository
 * @package App\Repositories
 */
class TagsRepository implements InterfacesRepository
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
        $this->model = new Tags();
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
     * @throws ExceptionsTags
     */
    public function lst(array $where, array $order, int $page, int $limit): array
    {
        // TODO: Implement lst() method.
        try {
            $offset = (new Check)->page($page, $limit); // 分页起始值
            $wheres = $this->array->getNotNull($where, 'int', ['status']); // 重置条件
            return $this->model->lst($wheres, $order, $offset, $limit); // 获取列表并返回
        }catch (Database $exception){
            throw new ExceptionsTags($exception->getMessage());
        }
    }

    /**
     * 总数
     *
     * @param array $where
     * @return int
     * @throws ExceptionsTags
     */
    public function count(array $where): int
    {
        // TODO: Implement count() method.
        $wheres = $this->array->getNotNull($where, 'int', ['status']); // 重置条件
        try {
            // 获取总数并返回
            return $this->model->count($wheres);
        }catch (Database $exception){
            throw new ExceptionsTags($exception->getMessage());
        }
    }

    /**
     * 添加
     *
     * @param array $data
     * @throws ExceptionsTags
     */
    public function insert(array $data): void
    {
        // TODO: Implement insert() method.
        // 重置添加数据
        $insert = $this->array->getNotNull($data, 'string', ['name'], ['status', 'int']);
        // 验证必要参数名称是否为空
        $checkKey = $this->array->isKey($insert, 'name');
        if(!$checkKey){ throw new ExceptionsTags("请填写必填参数值"); }
        // 验证名称是否重复
        $checkName = $this->model->get($this->model->table, ['name'], [], ['name' => $insert['name']], [], ['offset' => 0, 'limit' => 1]);
        if(count($checkName)) { throw new ExceptionsTags("名称已存在，不能重复添加"); }
        $insert['is_del'] = 0;
        $insert['add_time'] = time();
        try {
            $this->model->create($insert);
        }catch (Database $exception){
            throw new ExceptionsTags($exception->getMessage());
        }
    }

    /**
     * 修改
     *
     * @param array $data
     * @param int $id
     * @throws ExceptionsTags
     */
    public function update(array $data, int $id): void
    {
        // TODO: Implement update() method.
        // 验证编号
        $checkId = $this->model->checkId($this->model->table, $id);
        if(!$checkId){ throw new ExceptionsTags("参数错误"); }
        // 重置修改数据
        $update = $this->array->getNotNull($data, 'string', ['name'], ['status', 'int']);
        // 验证必要参数名称是否为空
        $checkKey = $this->array->isKey($update, 'name');
        if(!$checkKey){ throw new ExceptionsTags("请填写必填参数值"); }
        // 验证名称是否重复
        $checkName = $this->model->get($this->model->table, ['id', 'name'], [], ['name' => $update['name']], [], ['offset' => 0, 'limit' => 2]);
        switch (count($checkName)) {
            case 0:break;
            case 1:if($checkName[0]['id'] !== $id) { throw new ExceptionsTags("名称已存在，不能重复添加"); } break;
            default: throw new ExceptionsTags("名称已存在，不能重复添加");
        }
        // 验证修改数据和数据库数据是否一致
        $checkUpdate = $this->model->first($this->model->table, $id, array_keys($update)) === $update;
        if($checkUpdate) { return; }
        try{
            // 修改数据
            $this->model->update($update, $id);
        }catch (Database $exception) {
            throw new ExceptionsTags($exception->getMessage());
        }
    }

    /**
     * 删除
     *
     * @param int $id
     * @throws ExceptionsTags
     */
    public function delete(int $id): void
    {
        // TODO: Implement delete() method.
        $checkId = $this->model->checkId($this->model->table, $id);
        if(!$checkId){ throw new ExceptionsTags("已删除，不能重复删除"); }
        try{
            $this->model->update(['is_del' => 1], $id); // 删除
        }catch (Database $exception) {
            throw new ExceptionsTags($exception->getMessage());
        }
    }

    /**
     * 批量验证编号
     *
     * @param array $ids
     * @return int
     */
    public function checkIds(array $ids): int
    {
        try{
            // 格式化编号为整数
            $ids = array_map(function ($item){ return (int)$item; }, $ids);
            return $this->model->checkIds($ids);
        }catch (Database $exception) {
            return 0;
        }
    }

    /**
     * 标签
     *
     * @return array
     */
    public function total(): array
    {
        return $this->model->get($this->model->table, ['id', 'name'], [], ['status' => 1], ['add_time' => 'DESC']);
    }
}
