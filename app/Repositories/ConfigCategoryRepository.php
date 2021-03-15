<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/8/4
 */

namespace App\Repositories;

use App\Exceptions\{ConfigCategory as ExceptionsConfigCategory, Database};
use App\CcForever\{
    interfaces\Repository as InterfacesRepository,
    service\Check,
    service\HandleArray,
    traits\Repository as TraitsRepository
};

use App\Models\ConfigCategory;

/**
 * 配置分类
 *
 * Class ConfigCategoryRepository
 * @package App\Repositories
 */
class ConfigCategoryRepository implements InterfacesRepository
{
    use TraitsRepository;

    /**
     * 数组处理
     *
     * @var HandleArray
     */
    private $array;

    public function __construct()
    {
        $this->model = new ConfigCategory();
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
     * @throws ExceptionsConfigCategory
     */
    public function lst(array $where, array $order, int $page, int $limit): array
    {
        // TODO: Implement lst() method.
        try {
            $offset = (new Check())->page($page, $limit); // 获取起始值
            return $this->model->lst([], $order, $offset, $limit);// 获取列表并返回
        }catch (Database $exception) {
            throw new ExceptionsConfigCategory($exception->getMessage());
        }
    }

    /**
     * 总数
     *
     * @param array $where
     * @return int
     * @throws ExceptionsConfigCategory
     */
    public function count(array $where): int
    {
        // TODO: Implement count() method.
        try {
            return $this->model->count([]);// 获取总数并返回
        }catch (Database $exception) {
            throw new ExceptionsConfigCategory($exception->getMessage());
        }
    }

    /**
     * 添加
     *
     * @param array $data
     * @throws ExceptionsConfigCategory
     */
    public function insert(array $data): void
    {
        // TODO: Implement insert() method.
        // 重置添加参数
        $insert = $this->array->getNotNull($data, 'string', ['name'], ['description']);
        // 验证必要参数名称是否存在
        $checkKey = $this->array->isKey($insert, 'name');
        if(!$checkKey){ throw new ExceptionsConfigCategory("请填写必填参数值"); }
        $insert['is_del'] = 0;
        $insert['add_time'] = time();
        try{
            $this->model->create($insert); // 添加
        }catch (Database $exception){
            throw new ExceptionsConfigCategory($exception->getMessage());
        }
    }

    /**
     * 修改
     *
     * @param array $data
     * @param int $id
     * @throws ExceptionsConfigCategory
     */
    public function update(array $data, int $id): void
    {
        // TODO: Implement update() method.
        // 验证编号
        $checkId = $this->model->checkId($this->model->table, $id);
        if(!$checkId){ throw new ExceptionsConfigCategory("参数错误"); }
        // 重置修改参数
        $update = $this->array->getNotNull($data, 'string', ['name'], ['description']);
        // 验证必要参数名称是否存在
        $checkKey = $this->array->isKey($update, 'name');
        if(!$checkKey){ throw new ExceptionsConfigCategory("请填写必填参数值"); }
        $checkUpdate = $this->model->first($this->model->table, $id, array_keys($update)) === $update;
        if($checkUpdate) { return; }
        try{
            $this->model->update($update, $id); // 修改
        }catch (Database $exception){
            throw new ExceptionsConfigCategory($exception->getMessage());
        }
    }

    /**
     * 删除
     *
     * @param int $id
     * @throws ExceptionsConfigCategory
     */
    public function delete(int $id): void
    {
        // TODO: Implement delete() method.
        // 验证编号
        $checkId = $this->model->checkId($this->model->table, $id);
        if(!$checkId){ throw new ExceptionsConfigCategory("已删除，不能重复删除"); }
        try{
            $this->model->update(['is_del' => 1], $id); // 删除
        }catch (Database $exception) {
            throw new ExceptionsConfigCategory($exception->getMessage());
        }
    }

    /**
     * 配置分类
     *
     * @return array
     */
    public function total(): array
    {
        return $this->model->get($this->model->table, ['id', 'name'], [], [], ['id' => 'ASC']);
    }
}
