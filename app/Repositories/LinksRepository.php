<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/8/14
 */

namespace App\Repositories;

use App\Exceptions\{
    Database, Links as ExceptionsLinks
};
use App\CcForever\{
    interfaces\Repository as InterfacesRepository, traits\Repository as TraitsRepository,
    service\Check,
    service\HandleArray,
};
use App\Models\Links;

/**
 * 友情链接
 *
 * Class LinksRepository
 * @package App\Repositories
 */
class LinksRepository implements InterfacesRepository
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
        $this->model = new Links();
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
     * @throws ExceptionsLinks
     */
    public function lst(array $where, array $order, int $page, int $limit): array
    {
        // TODO: Implement lst() method.
        try{
            // 重置where条件
            $wheres = $this->array->getNotNull($where, 'int', ['follow']);
            // 分页起始值
            $offset = (new Check())->page($page, $limit);
            // 获取列表并返回
            return $this->model->lst($wheres, $order, $offset, $limit);
        }catch (Database $exception){
            throw new ExceptionsLinks($exception->getMessage());
        }

    }

    /**
     * 总数
     *
     * @param array $where
     * @return int
     * @throws ExceptionsLinks
     */
    public function count(array $where): int
    {
        // TODO: Implement count() method.
        try{
            // 重置where条件
            $wheres = $this->array->getNotNull($where, 'int', ['follow']);
            // 获取总数并返回
            return $this->model->count($wheres);
        }catch (Database $exception){
            throw new ExceptionsLinks($exception->getMessage());
        }
    }

    /**
     * 添加
     *
     * @param array $data
     * @throws ExceptionsLinks
     */
    public function insert(array $data): void
    {
        // TODO: Implement insert() method.
        // 重置添加数据
        $insert = $this->array->getNotNull($data, 'string', ['name'], ['link'], ['image'], ['weight', 'int'], ['follow', 'int']);
        // 验证必要参数名称、链接是否存在
        $checkKey = $this->array->isKey($insert, 'name', 'link');
        if(!$checkKey){ throw new ExceptionsLinks("请填写必填参数值"); }
        // 验证地址格式
        $checkLink = (bool)filter_var($insert['link'], FILTER_VALIDATE_URL);
        if(!$checkLink) { throw new ExceptionsLinks("链接格式错误");  }
        $insert['is_del'] = 0; // 是否删除  是 1  否 0
        $insert['add_time'] = time();  // 添加时间
        try{
            $this->model->create($insert); // 添加
        }catch (Database $exception){
            throw new ExceptionsLinks($exception->getMessage());
        }
    }

    /**
     * 修改
     *
     * @param array $data
     * @param int $id
     * @throws ExceptionsLinks
     */
    public function update(array $data, int $id): void
    {
        // TODO: Implement update() method.
        // 验证编号
        $checkId = $this->model->checkId($this->model->table, $id);
        if(!$checkId){ throw new ExceptionsLinks("参数错误"); }
        // 重置修改数据
        $update = $this->array->getNotNull($data, 'string', ['name'], ['link'], ['image'], ['weight', 'int'], ['follow', 'int']);
        // 验证必要参数名称、链接是否存在
        $checkKey = $this->array->isKey($update, 'name', 'link');
        if(!$checkKey){ throw new ExceptionsLinks("请填写必填参数值"); }
        // 验证地址格式
        $checkLink = (bool)filter_var($update['link'], FILTER_VALIDATE_URL);
        if(!$checkLink) { throw new ExceptionsLinks("链接格式错误");  }
        $checkUpdate = $this->model->first($this->model->table, $id, array_keys($update)) == $update;
        if($checkUpdate) { return; }
        try{
            $this->model->update($update, $id); // 修改
        }catch (Database $exception){
            throw new ExceptionsLinks($exception->getMessage());
        }
    }

    /**
     * 删除
     *
     * @param int $id
     * @throws ExceptionsLinks
     */
    public function delete(int $id): void
    {
        // TODO: Implement delete() method.
        // 验证编号
        $checkId = $this->model->checkId($this->model->table, $id);
        if(!$checkId){ throw new ExceptionsLinks("已删除，不能重复删除"); }
        try{
            $this->model->update(['is_del' => 1], $id); // 删除
        }catch (Database $exception) {
            throw new ExceptionsLinks($exception->getMessage());
        }
    }

    /**
     * 友情链接
     *
     * @return array
     */
    public function links(): array
    {
        return $this->model->get($this->model->table, ['name', 'image', 'link', 'follow'], [], [], ['weight' => 'ASC']);
    }
}
