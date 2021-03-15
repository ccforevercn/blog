<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/8/4
 */
namespace App\Repositories;

use App\Exceptions\{ConfigMessage as ExceptionsConfigMessage, Database};
use App\CcForever\{
    interfaces\Repository as InterfacesRepository,
    service\Check,
    service\HandleArray,
    traits\Repository as TraitsRepository
};
use App\Models\ConfigMessage;

/**
 * 配置信息
 *
 * Class ConfigMessageRepository
 * @package App\Repositories
 */
class ConfigMessageRepository implements InterfacesRepository
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
        $this->model = new ConfigMessage();
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
     * @throws ExceptionsConfigMessage
     */
    public function lst(array $where, array $order, int $page, int $limit): array
    {
        // TODO: Implement lst() method.
        try {
            // 重置where条件
            $wheres = $this->array->getNotNull($where, 'int', ['category_id']);
            if(!$this->array->isKey($wheres, 'category_id')) { throw new ExceptionsConfigMessage("参数错误"); }
            $offset = (new Check())->page($page, $limit); // 获取起始值
            return $this->model->lst($wheres, $order, $offset, $limit);// 获取列表并返回
        }catch (Database $exception) {
            throw new ExceptionsConfigMessage($exception->getMessage());
        }
    }

    /**
     * 总数
     *
     * @param array $where
     * @return int
     * @throws ExceptionsConfigMessage
     */
    public function count(array $where): int
    {
        // TODO: Implement count() method.
        try {
            // 重置where条件
            $wheres = $this->array->getNotNull($where, 'int', ['category_id']);
            if(!$this->array->isKey($wheres, 'category_id')) { throw new ExceptionsConfigMessage("参数错误"); }
            return $this->model->count($wheres);// 获取总数并返回
        }catch (Database $exception) {
            throw new ExceptionsConfigMessage($exception->getMessage());
        }
    }

    /**
     * 添加
     *
     * @param array $data
     * @throws ExceptionsConfigMessage
     */
    public function insert(array $data): void
    {
        // TODO: Implement insert() method.
        // 重置添加参数
        $insert = $this->array->getNotNull($data, 'string', ['name'], ['description'], ['select'], ['category_id', 'int'], ['type', 'int'], ['type_value'], ['value'], ['type_value'], ['is_show', 'int']);
        // 验证必要参数名称、唯一值、分类编号、类型是否存在
        $checkKey = $this->array->isKey($insert, 'name', 'select', 'category_id', 'type');
        if(!$checkKey){ throw new ExceptionsConfigMessage("请填写必填参数值"); }
        // 验证唯一值
        try {
            $checkSelect = $this->model->count(['select' => $insert['select']]);
            if($checkSelect){ throw new ExceptionsConfigMessage("唯一值已存在"); }
        }catch (Database $exception){
            throw new ExceptionsConfigMessage($exception->getMessage());
        }
        // 验证分类编号
        $checkCategoryId = (new ConfigCategoryRepository())->checkId($insert['category_id']);
        if(!$checkCategoryId){ throw new ExceptionsConfigMessage("配置分类不存在"); }
        // 验证类型为单选或者多选
        if((int)$insert['type'] === 2 || (int)$insert['type'] === 3) {
            try {
                // 验证类型值和默认值
                $checkNull = $this->array->isKey($insert, 'type_value', 'value');
                if(!$checkNull){ throw new ExceptionsConfigMessage("请填写必填参数值"); }
                $this->checkTypeValue((int)$insert['type'] === 2, $insert['type_value'], $insert['value']);
            }catch (ExceptionsConfigMessage $exception) {
                throw new ExceptionsConfigMessage($exception->getMessage());
            }
        }
        $insert['is_del'] = 0;
        $insert['add_time'] = time();
        try{
            $this->model->create($insert); // 添加
        }catch (Database $exception){
            throw new ExceptionsConfigMessage($exception->getMessage());
        }
    }

    /**
     * 修改
     *
     * @param array $data
     * @param int $id
     * @throws ExceptionsConfigMessage
     */
    public function update(array $data, int $id): void
    {
        // TODO: Implement update() method.
        // 验证编号
        $checkId = $this->model->checkId($this->model->table, $id);
        if(!$checkId){ throw new ExceptionsConfigMessage("参数错误"); }
        // 重置修改参数
        $update = $this->array->getNotNull($data, 'string', ['name'], ['description'], ['value'], ['is_show', 'int']);
        // 验证必要参数名称是否存在
        $checkKey = $this->array->isKey($update, 'name');
        if(!$checkKey){ throw new ExceptionsConfigMessage("请填写必填参数值"); }
        $updateKey = array_keys($update); // 修改字段
        $attach = ['type', 'type_value']; // 额外字段
        $total = array_merge($updateKey, $attach); // 查询字段
        $message = $this->model->first($this->model->table, $id, $total); // 配置信息
        // 验证修改数据和数据库是否一致 array_intersect_assoc()  获取$message中和$update同时存在的键值和键值的值
        if(array_intersect_key($message, $update) == $update){ return; }
        if((int)$message['type'] === 2 || (int)$message['type'] === 3) {
            try {
                // 验证默认值
                $this->checkTypeValue((int)$message['type'] === 2, $message['type_value'], $update['value']);
            }catch (ExceptionsConfigMessage $exception) {
                throw new ExceptionsConfigMessage($exception->getMessage());
            }
        }
        try{
            $this->model->update($update, $id); // 修改
        }catch (Database $exception){
            throw new ExceptionsConfigMessage($exception->getMessage());
        }
    }

    /**
     * 验证类型值和默认值格式
     *
     * @param bool $radio
     * @param string $type
     * @param string $value
     * @throws ExceptionsConfigMessage
     */
    private function checkTypeValue(bool $radio, string $type, string $value): void
    {
        // 验证类型值
        if(!strlen($type)){ throw new ExceptionsConfigMessage("请填写类型值"); }
        // 验证类型值格式
        $arrayType = $this->array->specialStringsToArray($type);
        if(!count($arrayType)) { throw new ExceptionsConfigMessage("类型值格式错误，请按照field:value|field:value..."); }
        // 验证默认值
        if(!strlen($value)){ throw new ExceptionsConfigMessage("请填写默认值"); }
        $arrayValue = explode('|', $value); // 使用|切割默认值转为数组
        $valueCount = 0; // 选中次数
        // 默认值在类型值中命中次数累加
        foreach ($arrayType as $key=>&$values){
            list($field) = $values;
            if(in_array($field, $arrayValue)){ $valueCount++; }
        }
        // 验证默认值为0 或者 默认值和类型值命中次数不匹配(类型值的value中没有默认值)
        $checkValue = !$valueCount || $valueCount !== count($arrayValue);
        if($checkValue){ throw new ExceptionsConfigMessage("请选择正确的默认值"); }
        // 验证单选只能选择一个值
        if($radio && $valueCount != 1) { throw new ExceptionsConfigMessage("单选默认值只能选择单个"); }
    }

    /**
     * 删除
     *
     * @param int $id
     * @throws ExceptionsConfigMessage
     */
    public function delete(int $id): void
    {
        // TODO: Implement delete() method.
        // 验证编号
        $checkId = $this->model->checkId($this->model->table, $id);
        if(!$checkId){ throw new ExceptionsConfigMessage("已删除，不能重复删除"); }
        try{
            $this->model->update(['is_del' => 1], $id); // 删除
        }catch (Database $exception) {
            throw new ExceptionsConfigMessage($exception->getMessage());
        }
    }

    /**
     * 配置(单个)
     *
     * @param string $select
     * @return string
     */
    public function config(string $select): string
    {
        return $this->model->config($select);
    }

    /**
     * 配置(多个)
     *
     * @param array $selects
     * @param bool $show
     * @return array
     */
    public function configs(array $selects, bool $show): array
    {
        return $this->model->configs($selects, $show);
    }
}
