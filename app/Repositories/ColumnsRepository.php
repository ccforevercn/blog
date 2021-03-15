<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/7/31
 */

namespace App\Repositories;

use App\Exceptions\{Columns as ExceptionsColumns, ColumnsContent as ExceptionsColumnsContent, Database};
use App\Models\Columns;
use App\CcForever\{
    interfaces\Repository as InterfacesRepository,
    service\Check,
    service\HandleArray,
    service\model\Columns as ServiceColumns,
    traits\Repository as TraitsRepository
};

/**
 * 栏目
 *
 * Class ColumnsRepository
 * @package App\Repositories
 */
class ColumnsRepository implements InterfacesRepository
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
        $this->model = new Columns();
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
     * @throws ExceptionsColumns
     */
    public function lst(array $where, array $order, int $page, int $limit): array
    {
        // TODO: Implement lst() method.
        try{
            // 重置where条件
            $wheres = $this->array->getNotNull($where, 'string', ['parent_id', 'int']);
            // 分页起始值
            $offset = (new Check())->page($page, $limit);
            // 获取列表并返回
            return $this->model->lst($wheres, $order, $offset, $limit);
        }catch (Database $exception){
            throw new ExceptionsColumns($exception->getMessage());
        }
    }

    /**
     * 总数
     *
     * @param array $where
     * @return int
     * @throws ExceptionsColumns
     */
    public function count(array $where): int
    {
        // TODO: Implement count() method.
        try{
            // 重置where条件
            $wheres = $this->array->getNotNull($where, 'string', ['parent_id', 'int']);
            // 获取总数并返回
            return $this->model->count($wheres);
        }catch (Database $exception){
            throw new ExceptionsColumns($exception->getMessage());
        }
    }

    /**
     * 添加
     *
     * @param array $data
     * @throws ExceptionsColumns
     */
    public function insert(array $data): void
    {
        // TODO: Implement insert() method.
        // 重置添加数据
        $insert = $this->array->getNotNull($data, 'string', ['name'], ['name_alias'], ['parent_id', 'int'], ['image'], ['banner_image'], ['keywords'], ['description'], ['weight', 'int'], ['limit', 'int'], ['sort', 'int'], ['navigation', 'int'], ['render', 'int'], ['page']);
        // 验证必要参数名称、父级编号、渲染类型、渲染页面是否存在
        $checkKey = $this->array->isKey($insert, 'name', 'parent_id', 'render', 'page');
        if(!$checkKey){ throw new ExceptionsColumns("请填写必填参数值"); }
        $insert['is_del'] = 0; // 是否删除  是 1  否 0
        $insert['add_time'] = time();  // 添加时间
        try{
            // 添加
            $this->model->create($insert);
        }catch (Database $exception){
            throw new ExceptionsColumns($exception->getMessage());
        }
    }

    /**
     * 修改
     *
     * @param array $data
     * @param int $id
     * @throws ExceptionsColumns
     */
    public function update(array $data, int $id): void
    {
        // TODO: Implement update() method.
        // 验证编号
        $checkId = $this->model->checkId($this->model->table, $id);
        if(!$checkId){ throw new ExceptionsColumns("参数错误"); }
        // 重置修改数据
        $update = $this->array->getNotNull($data, 'string', ['name'], ['name_alias'], ['parent_id', 'int'], ['image'], ['banner_image'], ['keywords'], ['description'], ['weight', 'int'], ['limit', 'int'], ['sort', 'int'], ['navigation', 'int'], ['render', 'int'], ['page']);
        // 验证必要参数名称、父级编号、渲染类型、渲染页面是否存在
        $checkKey = $this->array->isKey($update, 'name', 'parent_id', 'render', 'page');
        if(!$checkKey){ throw new ExceptionsColumns("参数错误"); }
        // 验证修改数据和数据库数据是否一致
        $checkUpdate = $this->model->first($this->model->table, $id, array_keys($update)) === $update;
        if($checkUpdate) { return; }
        try{
            // 修改数据
            $this->model->update($update, $id);
        }catch (Database $exception) {
            throw new ExceptionsColumns($exception->getMessage());
        }
    }

    /**
     * 删除
     *
     * @param int $id
     * @throws ExceptionsColumns
     */
    public function delete(int $id): void
    {
        // TODO: Implement delete() method.
        // 验证编号
        $checkId = $this->model->checkId($this->model->table, $id);
        if(!$checkId){ throw new ExceptionsColumns("已删除，不能重复删除"); }
        try{
            // 验证是否有下级栏目
            $list = $this->model->lst(['parent_id' => $id], [], 0, 1);
            if(count($list)) { throw new ExceptionsColumns("请先删除子栏目"); }
            $this->model->update(['is_del' => 1], $id);
        }catch (Database $exception) {
            throw new ExceptionsColumns($exception->getMessage());
        }
    }

    /**
     * 内容添加、修改、查询
     *
     * @param array $data
     * @param int $id
     * @param bool $type
     * @return array
     * @throws ExceptionsColumns
     */
    public function content(array $data, int $id, bool $type): array
    {
        // 验证编号
        $checkId = $this->model->checkId($this->model->table, $id);
        if(!$checkId){ throw new ExceptionsColumns("参数错误"); }
        $columnsContent = new ColumnsContentRepository(); // 获取栏目内容类
        switch ($type) {
            case true: // 查询
                return $columnsContent->message($id, ['content', 'markdown']); // 返回查询信息
            default:
                try {
                    // 重置参数
                    $param = $this->array->getNotNull($data, 'string', ['content'], ['markdown']);
                    // 验证必要值是否存在
                    if(!array_key_exists('content', $param)) { throw new ExceptionsColumns("请填写必要参数"); }
                    // 验证栏目内容编号
                    $checkId = $columnsContent->checkId($id);
                    if($checkId) {
                        // 栏目内容编号存在，修改
                        // 验证修改数据和数据库数据是否一致
                        $checkUpdate = $columnsContent->message($id, array_keys($param)) === $param;
                        // 不一致修改
                        if(!$checkUpdate) { $columnsContent->update($param, $id); }
                    }else {
                        // 栏目内容编号不存在，添加
                        $columnsContent->insert(array_merge($param, ['id' => $id, 'is_del' => 0]));
                    }
                    if(!array_key_exists('markdown', $param)) { $param['markdown'] = ''; }
                    return $param; // 返回添加、修改数据
                }catch (ExceptionsColumnsContent $exception) {
                    throw new ExceptionsColumns($exception->getMessage());
                }
        }
    }

    /**
     * 栏目
     *
     * @param array $selects
     * @param array $where
     * @param array $order
     * @param int $page
     * @param int $Limit
     * @return array
     */
    public function total(array $selects, array $where, array $order, int $page, int $Limit): array
    {
        $limits = []; // 分页
        if($page && $Limit) {
            // 分页起始值
            $offset = (new Check())->page($page, $Limit);
            $limits = compact('offset', 'limit');
        }
        return $this->model->get($this->model->table, $selects, [], $where, $order, $limits);
    }

    /**
     * 导航
     *
     * @return array
     */
    public function navigation(): array
    {
        // 查询字段
        $selects = ['id', 'name', 'name_alias', 'parent_id', 'render', 'page'];
        $where = ['navigation' => '1']; //查询条件
        $order = ['weight' => 'ASC']; // 排序方式
        // 栏目列表
        $columns = $this->model->get($this->model->table, $selects, [], $where, $order);
        $suffix = (new ConfigMessageRepository())->config('config_page_suffix'); // 内网链接后缀
        // 格式化导航并返回
        return (new ServiceColumns())->navigation($columns, 0, '/', $suffix);
    }

    /**
     * 栏目列表
     *
     * @param array $ids
     * @return array
     * @throws ExceptionsColumns
     */
    public function messages(array $ids): array
    {
        try {
            return $this->model->messages($ids); // 获取栏目列表并返回
        }catch (Database $exception) {
            throw new ExceptionsColumns($exception->getMessage());
        }
    }

    /**
     * 栏目
     *
     * @param int $id
     * @param array $selects
     * @return array
     */
    public function message(int $id, array $selects = []): array
    {
        // 重置查询字段
        if(!count($selects)){  $selects = ['id', 'name', 'name_alias', 'image', 'banner_image', 'keywords', 'parent_id', 'render', 'description', 'sort', 'page', 'limit']; }
        return $this->model->first($this->model->table, $id, $selects);
    }


    /**
     * 顶级栏目
     *
     * @param int $id
     * @return array
     */
    public function firstColumn(int $id): array
    {
        // 验证编号
        $checkId = $this->model->checkId($this->model->table, $id);
        if(!$checkId){ return []; }
        $selects = ['id', 'name', 'name_alias', 'image', 'banner_image', 'keywords', 'parent_id', 'description', 'page', 'render', 'sort'];
        $columns = $this->total($selects, [], [], 0, 0);
        $column = (new ServiceColumns())->firstColumn($columns, $id);
        if(!count($column)) return [];
        return $column;
    }

    /**
     * 子栏目
     *
     * @param int $id
     * @param int $limit
     * @return array
     */
    public function children(int $id, int $limit): array
    {
        // 验证编号
        $checkId = $this->model->checkId($this->model->table, $id);
        if(!$checkId){ return []; }
        // 查询字段
        $selects = ['id', 'name', 'name_alias', 'keywords', 'description', 'page', 'render'];
        $where = ['parent_id' => $id];  // 条件
        $order = ['weight' => 'ASC'];  // 排序方式
        return $this->total($selects, $where, $order, 1, $limit);
    }

    /**
     * 子栏目+当前栏目编号
     *
     * @param int $id
     * @return array
     */
    public function subsetIds(int $id): array
    {
        // 验证编号
        $checkId = $this->model->checkId($this->model->table, $id);
        if(!$checkId){ return []; }
        // 栏目列表
        $columns = $this->total(['id', 'parent_id'], [], ['weight' => 'ASC'], 0, 0);
        // 获取子栏目
        $subsets = (new ServiceColumns())->subsets($columns, $id, []);
        // 获取子栏目编号和当前栏目编号并返回
        return array_merge([$id], array_map(function ($item){ return $item['id']; }, $subsets));
    }
}
