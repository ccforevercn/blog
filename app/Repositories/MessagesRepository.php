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
    service\Time,
    traits\Repository as TraitsRepository
};
use App\Exceptions\{
    Database, Messages as ExceptionsMessages, MessagesContent as ExceptionsMessagesContent, MessagesTags as ExceptionsMessagesTags
};
use App\Models\Messages;

/**
 * 信息
 *
 * Class MessagesRepository
 * @package App\Repositories
 */
class MessagesRepository implements InterfacesRepository
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
        $this->model = new Messages();
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
     * @throws ExceptionsMessages
     */
    public function lst(array $where, array $order, int $page, int $limit): array
    {
        // TODO: Implement lst() method.
        try{
            $wheres = $this->array->getNotNull($where, 'int', ['columns_id', 'array'], ['index'], ['hot']); // 重置条件
            $offset = (new Check)->page($page, $limit); // 获取起始值
            return $this->model->lst($wheres, $order, $offset, $limit); // 获取列表并返回
        }catch (Database $exception){
            throw new ExceptionsMessages($exception->getMessage());
        }
    }

    /**
     * 总数
     *
     * @param array $where
     * @return int
     * @throws ExceptionsMessages
     */
    public function count(array $where): int
    {
        // TODO: Implement count() method.
        $wheres = $this->array->getNotNull($where, 'int', ['columns_id', 'array'], ['index'], ['hot']); // 重置条件
        try{
            // 获取列表并返回
            return $this->model->count($wheres);
        }catch (Database $exception){
            throw new ExceptionsMessages($exception->getMessage());
        }
    }

    /**
     * 添加
     *
     * @param array $data
     * @throws ExceptionsMessages
     */
    public function insert(array $data): void
    {
        // TODO: Implement insert() method.
        try {
            list($insert, $insertTags) = $this->param($data);
            // 重置权重值
            if(!array_key_exists('weight', $insert)){
                try{
                    $weight = $this->model->count(['columns_id' => $insert['columns_id']]);
                    $insert['weight'] = (int)bcadd($weight, 1, 0);
                }catch (Database $exception){
                    $insert['weight'] = 1;
                }
            }
            $insert['add_time'] = time(); // 添加时间
            $format['is_del'] = 0; // 是否删除 是 1  否 0
            // 修改时间
            if(!array_key_exists('update_time', $insert)){ $insert['update_time'] = time(); }
            $this->model->begin(); // 开启事务
            $insertStatus = true; // 信息添加状态
            $insertTagsStatus = true; // 信息标签添加状态
            $insertErrorMessage = "添加失败"; // 添加失败提示信息
            try {
                $this->model->create($insert); // 添加信息
            }catch (Database $exception) {
                $insertStatus = false; // 重置信息添加状态
                // 重置添加失败提示信息
                $insertErrorMessage = $exception->getMessage();
            }
            // 标签存在
            if(count($insertTags) && $insertStatus) {
                try {
                    (new MessagesTagsRepository())->insert($insertTags); // 添加标签
                }catch (ExceptionsMessagesTags $exception) {
                    $insertTagsStatus = false; // 重置信息标签添加状态
                    // 重置添加失败提示信息
                    $insertErrorMessage = $exception->getMessage();
                }
            }
            $res = $insertStatus && $insertTagsStatus; // 检测添加状态
            $this->model->checkBegin($res); // 验证事务
            // 添加失败抛出异常
            if(!$res) { throw new ExceptionsMessages($insertErrorMessage); }
        }catch (ExceptionsMessages $exception) {
            throw new ExceptionsMessages($exception->getMessage());
        }
    }

    /**
     * 修改
     *
     * @param array $data
     * @param int $id
     * @throws ExceptionsMessages
     */
    public function update(array $data, int $id): void
    {
        // TODO: Implement update() method.
        // 验证编号
        $checkId = $this->model->checkId($this->model->table, $id);
        if(!$checkId){ throw new ExceptionsMessages("参数错误"); }
        try {
            // 重置添加数据
            list($update, $insertTags) = $this->param($data);
            $this->model->begin(); // 开启事务
            $updateStatus = true; // 信息修改状态
            $insertTagsStatus = true; // 信息标签添加状态
            $updateErrorMessage = "添加失败"; // 修改失败提示信息
            try {
                $this->model->update($update, $id); // 修改信息
            }catch (Database $exception) {
                $updateStatus = false; // 重置信息修改状态
                // 重置修改失败提示信息
                $updateErrorMessage = $exception->getMessage();
            }
            // 标签存在
            if(count($insertTags) && $updateStatus) {
                try {
                    (new MessagesTagsRepository())->insert($insertTags); // 添加标签
                }catch (ExceptionsMessagesTags $exception) {
                    $insertTagsStatus = false; // 重置信息标签添加状态
                    // 重置添加失败提示信息
                    $updateErrorMessage = $exception->getMessage();
                }
            }
            $res = $updateStatus && $insertTagsStatus; // 检测修改状态
            $this->model->checkBegin($res); // 验证事务
            // 修改失败抛出异常
            if(!$res) { throw new ExceptionsMessages($updateErrorMessage); }
        }catch (ExceptionsMessages $exception) {
            throw new ExceptionsMessages($exception->getMessage());
        }
    }

    /**
     * 删除
     *
     * @param int $id
     * @throws ExceptionsMessages
     */
    public function delete(int $id): void
    {
        // TODO: Implement delete() method.
        // 验证编号
        $checkId = $this->model->checkId($this->model->table, $id);
        if(!$checkId){ throw new ExceptionsMessages("已删除，不能重复删除"); }
        try{
            // 删除
            $this->model->update(['is_del' => 1], $id);
        }catch (Database $exception) {
            throw new ExceptionsMessages($exception->getMessage());
        }
    }

    /**
     * 参数获取(添加、删除)
     *
     * @param array $param
     * @return array
     * @throws ExceptionsMessages
     */
    private function param(array $param): array
    {
        // 重置数据
        $format = $this->array->getNotNull($param, 'string', ['name'], ['columns_id', 'int'], ['tags_id'], ['image'], ['writer'], ['click', 'int'], ['weight', 'int'], ['keywords'], ['description'], ['index', 'int'], ['hot', 'int'], ['update_time'], ['page']);
        // 验证必要参数名称，栏目编号、模板页是否为空
        $checkKey = $this->array->isKey($format, 'name', 'columns_id', 'page');
        if(!$checkKey){ throw new ExceptionsMessages("请填写必填参数值"); }
        $formatTags = []; // 信息标签
        $formatTagsCount = 0; // 信息标签添加总数
        $unique = md5((new Time())->millisecond().uniqid()); // 信息和信息标签的唯一值
        // 信息标签存在
        if(array_key_exists('tags_id', $format)){
            $tagsIdsArr = explode(',', $format['tags_id']); // 格式化标签编号
            $count = (new TagsRepository())->checkIds($tagsIdsArr); // 批量验证编号
            // 编号和数据库编号不一致
            if($count !== count($tagsIdsArr)){ throw new ExceptionsMessages("标签不存在");  }
            // 格式化信息标签
            foreach ($tagsIdsArr as $key=>$tagsId){
                $formatTags[$formatTagsCount]['tag_id'] = (int)$tagsId; // 标签编号
                $formatTags[$formatTagsCount]['unique'] = $unique; // 唯一值
                $formatTags[$formatTagsCount]['add_time'] = time(); // 添加时间
                $formatTags[$formatTagsCount]['clear_time'] = time(); // 清除时间 默认添加时间
                $formatTags[$formatTagsCount]['is_del'] = 0; // 是否删除  删除时修改清除时间
                $formatTagsCount++; // key自增
            }
        }
        // 验证栏目编号
        $checkColumnsId = (new ColumnsRepository())->checkId($format['columns_id']);
        if(!$checkColumnsId){  throw new ExceptionsMessages("栏目不存在"); }
        $format['unique'] = $unique;  // 标签唯一值
        unset($format['tags_id']);
        return [$format, $formatTags];
    }

    /**
     * 内容 添加/修改/查询
     *
     * @param array $data
     * @param int $id
     * @param bool $type
     * @return array
     * @throws ExceptionsMessages
     */
    public function content(array $data, int $id, bool $type): array
    {
        // 验证编号
        $checkId = $this->model->checkId($this->model->table, $id);
        if(!$checkId){ throw new ExceptionsMessages("参数错误"); }
        $messagesContent = new MessagesContentRepository();
        switch ($type) {
            case true: // 查询
                return $messagesContent->message($id, ['content', 'markdown']); // 返回查询信息
            default:
                try {
                    // 重置参数
                    $param = $this->array->getNotNull($data, 'string', ['content'], ['markdown']);
                    // 验证必要值是否存在
                    if (!array_key_exists('content', $param)) {
                        throw new ExceptionsMessages("请填写必要参数");
                    }
                    // 验证栏目内容编号
                    $checkId = $messagesContent->checkId($id);
                    if ($checkId) {
                        // 栏目内容编号存在，修改
                        // 验证修改数据和数据库数据是否一致
                        $checkUpdate = $messagesContent->message($id, array_keys($param)) === $param;
                        // 不一致修改
                        if (!$checkUpdate) {
                            $messagesContent->update($param, $id);
                        }
                    } else {
                        // 栏目内容编号不存在，添加
                        $messagesContent->insert(array_merge($param, ['id' => $id, 'is_del' => 0]));
                    }
                    if (!array_key_exists('markdown', $param)) {
                        $param['markdown'] = '';
                    }
                    return $param; // 返回添加、修改数据
                }catch (ExceptionsMessagesContent $exception) {
                    throw new ExceptionsMessages($exception->getMessage());
                }
        }
    }

    /**
     * 标签
     *
     * @param int $id
     * @return array
     * @throws ExceptionsMessages
     */
    public function tags(int $id): array
    {
        // 验证编号
        $checkId = $this->model->checkId($this->model->table, $id);
        if(!$checkId){ throw new ExceptionsMessages("参数错误"); }
        try {
            // 获取信息标签
            return (new MessagesTagsRepository())->tags($id);
        }catch (ExceptionsMessagesTags $exception) {
            throw new ExceptionsMessages($exception->getMessage());
        }
    }

    /**
     * 点击量/权重修改
     *
     * @param int $id
     * @param string $type
     * @param int $value
     * @throws ExceptionsMessages
     */
    public function number(int $id, string $type, int $value): void
    {
        // 验证编号
        $checkId = $this->model->checkId($this->model->table, $id);
        if(!$checkId){ throw new ExceptionsMessages("参数错误"); }
        // 验证修改字段类型
        if(!in_array($type, ['click', 'weight', 'index', 'hot'])) { throw new ExceptionsMessages("状态类型错误"); }
        // 验证修改值
        switch ($type) {
            case 'click':
            case 'weight':
                if(!($value < 999999 && $value >= 0)) { throw new ExceptionsMessages("范围之能在0-999999之间"); }
                break;
            default:
                if(!in_array($value, [0, 1])) { throw new ExceptionsMessages("状态错误"); }
        }
        $update = [$type => $value];
        $checkUpdate = $this->model->first($this->model->table, $id, [$type]) === $update;
        if($checkUpdate){ return; }
        try {
            $this->model->update($update, $id); // 修改信息
        }catch (Database $exception) {
            throw new ExceptionsMessages($exception->getMessage());
        }
    }

    /**
     * 信息(包含内容和标签)
     *
     * @param array $select
     * @param array $where
     * @param array $order
     * @param int $page
     * @param int $limit
     * @return array
     * @throws ExceptionsMessages
     */
    public function messages(array $select, array $where, array $order, int $page, int $limit): array
    {
        try {
            $limits = [];
            if($page && $limit){
                $offset = (new Check)->page($page, $limit); // 获取起始值
                $limits = compact('offset', 'limit');
            }
            return $this->model->messages($select, $where, $order, $limits);
        }catch (Database $exception) {
            throw new ExceptionsMessages($exception->getMessage());
        }
    }

    /**
     * 分页(上一页、下一页)
     *
     * @param int $id
     * @param array $selects
     * @param array $param
     * @return array
     */
    public function paging(int $id, array $selects, array $param): array
    {
        try {
            $wheres[] = ['select' => $param['select'], 'condition' => $param['condition'], 'value' => $param['message']]; // 添加筛选条件
            $wheres[] = ['select' => 'id', 'condition' => '<>', 'value' => $id]; // 添加筛选条件
            $order = [$param['select'] => $param['value']]; // 添加排序方式
            return $this->model->paging($selects, $wheres, $order);
        }catch (Database $exception) {
            return [];
        }
    }

    /**
     * 信息
     *
     * @param array $selects
     * @param array $where
     * @param array $order
     * @param array $limit
     * @return array
     */
    public function total(array $selects, array $where = [], array $order = [], array $limit = []): array
    {
        return $this->model->get($this->model->table, $selects, [], $where, $order, $limit);
    }
}
