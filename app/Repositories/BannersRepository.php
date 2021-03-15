<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/8/5
 */
namespace App\Repositories;

use App\Exceptions\{
    Database, Banners as ExceptionsBanners
};
use App\Models\Banners;
use App\CcForever\{
    interfaces\Repository as InterfacesRepository, traits\Repository as TraitsRepository,
    service\Check,
    service\HandleArray,
};

/**
 * 轮播图
 *
 * Class BannersRepository
 * @package App\Repositories
 */
class BannersRepository implements InterfacesRepository
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
        $this->model = new Banners();
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
     * @throws ExceptionsBanners
     */
    public function lst(array $where, array $order, int $page, int $limit): array
    {
        // TODO: Implement lst() method.
        try{
            // 重置where条件
            $wheres = $this->array->getNotNull($where, 'string', ['type', 'int']);
            // 分页起始值
            $offset = (new Check())->page($page, $limit);
            // 获取列表并返回
            return $this->model->lst($wheres, $order, $offset, $limit);
        }catch (Database $exception){
            throw new ExceptionsBanners($exception->getMessage());
        }
    }

    /**
     * 总数
     *
     * @param array $where
     * @return int
     * @throws ExceptionsBanners
     */
    public function count(array $where): int
    {
        // TODO: Implement count() method.
        try{
            // 重置where条件
            $wheres = $this->array->getNotNull($where, 'string', ['type', 'int']);
            // 获取总数并返回
            return $this->model->count($wheres);
        }catch (Database $exception){
            throw new ExceptionsBanners($exception->getMessage());
        }
    }

    /**
     * 添加
     *
     * @param array $data
     * @throws ExceptionsBanners
     */
    public function insert(array $data): void
    {
        // TODO: Implement insert() method.
        // 重置添加数据
        $insert = $this->array->getNotNull($data, 'string', ['name'], ['link'], ['image'], ['weight', 'int']);
        // 验证必要参数名称、图片地址、类型是否存在
        $checkKey = $this->array->isKey($insert, 'name', 'image');
        if(!$checkKey){ throw new ExceptionsBanners("请填写必填参数值"); }
        // 验证地址格式
        $checkLink = array_key_exists('link', $insert) && strlen($insert['link']);
        if($checkLink) {
            $checkLink = (bool)filter_var($insert['link'], FILTER_VALIDATE_URL);
            if(!$checkLink) { throw new ExceptionsBanners("地址格式错误");  }
        }
        $insert['is_del'] = 0; // 是否删除  是 1  否 0
        $insert['add_time'] = time();  // 添加时间
        try{
            $this->model->create($insert); // 添加
        }catch (Database $exception){
            throw new ExceptionsBanners($exception->getMessage());
        }
    }

    /**
     * 修改
     *
     * @param array $data
     * @param int $id
     * @throws ExceptionsBanners
     */
    public function update(array $data, int $id): void
    {
        // TODO: Implement update() method.
        // 验证编号
        $checkId = $this->model->checkId($this->model->table, $id);
        if(!$checkId){ throw new ExceptionsBanners("参数错误"); }
        // 重置修改数据
        $update = $this->array->getNotNull($data, 'string', ['name'], ['link'], ['image'], ['weight', 'int']);
        // 验证必要参数名称、图片地址、类型是否存在
        $checkKey = $this->array->isKey($update, 'name', 'image');
        if(!$checkKey){ throw new ExceptionsBanners("请填写必填参数值"); }
        // 验证地址格式
        $checkLink = array_key_exists('link', $update) && strlen($update['link']);
        if($checkLink) {
            $checkLink = (bool)filter_var($update['link'], FILTER_VALIDATE_URL);
            if(!$checkLink) { throw new ExceptionsBanners("地址格式错误");  }
        }
        $checkUpdate = $this->model->first($this->model->table, $id, array_keys($update)) === $update;
        if($checkUpdate) { return; }
        try{
            $this->model->update($update, $id); // 修改
        }catch (Database $exception){
            throw new ExceptionsBanners($exception->getMessage());
        }
    }

    /**
     * 删除
     *
     * @param int $id
     * @throws ExceptionsBanners
     */
    public function delete(int $id): void
    {
        // TODO: Implement delete() method.
        // 验证编号
        $checkId = $this->model->checkId($this->model->table, $id);
        if(!$checkId){ throw new ExceptionsBanners("已删除，不能重复删除"); }
        try{
            $this->model->update(['is_del' => 1], $id); // 删除
        }catch (Database $exception) {
            throw new ExceptionsBanners($exception->getMessage());
        }
    }

    /**
     * 轮播图
     *
     * @return array
     */
    public function banners(): array
    {
        return $this->model->get($this->model->table, ['name', 'image', 'link'], [], [], ['weight' => 'ASC']);
    }
}
