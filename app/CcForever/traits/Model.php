<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/7/20
 */

namespace App\CcForever\traits;

use App\CcForever\service\Util;
use Illuminate\Support\Facades\DB;

trait Model
{
    /**
     * @var DB
     */
    private $db;

    /**
     * 数据表前缀
     *
     * @var String
     */
    private $prefix;

    public function __construct()
    {
        $this->db = new DB(); // DB操作类
        $this->prefix = (new Util())->tablePrefix();  // 表前缀
    }

    /**
     * 检测编号
     *
     * @param string $table
     * @param int $id
     * @return bool
     */
    public function checkId(string $table, int $id): bool
    {
        $sql = "select count(id) as count from ".$this->prefix . $table." where is_del = 0 AND id = ?";
        $result = $this->db::selectOne($sql, [$id]);
        return is_null($result) || !$result->count ? false : true;
    }

    /**
     * 编号查询
     *
     * @param string $table
     * @param int $id
     * @param array $selects
     * @return array
     */
    public function first(string $table, int $id, array $selects): array
    {
        $sql = "SELECT `".implode('`,`', $selects)."` FROM ".$this->prefix . $table." WHERE `is_del` = 0 AND `id` = ?";
        $result = $this->db::selectOne($sql, [$id]);
        return is_null($result) ? [] : (array)$result;
    }

    /**
     * 列表
     *
     * @param string $table
     * @param array $selects
     * @param array $selectSql
     * @param array $where
     * @param array $orderBy
     * @param array $limit
     * @return array
     */
    public function get(string $table, array $selects, array $selectSql = [], array $where = [], array $orderBy = [], array $limit = []): array
    {
        $sql = "SELECT `".implode('`,`', $selects)."`". implode(',', $selectSql) ." FROM ".$this->prefix . $table;
        $this->select($selects, $selectSql);
        $sql .= $this->where($where); // 条件
        $sql .= $this->order($orderBy); // 排序
        $sql .= $this->limit($limit); // 条数
        $result = $this->db::select($sql); // 执行
        // 获取参数并返回
        return array_map(function ($item) use($selects){
            $menu = [];
            foreach ($selects as $select) { $menu[$select] = $item->$select; }
            return  $menu;
        },$result);
    }

    /**
     * 查询字段处理
     *
     * @param array $selects
     * @param array $selectSql
     * @return string
     */
    private function select(array $selects, array $selectSql = []): string
    {
        if(count($selects)) { return "`".implode('`,`', $selects)."`". implode(',', $selectSql); }
        return implode(',', $selectSql);
    }

    /**
     * 查询条件处理
     *
     * @param array $where
     * @return string
     */
    private function where(array $where): string
    {
        $sql = " WHERE ".$this->prefix.$this->table.".`is_del` = 0";
        if(count($where)) {
            foreach ($where as $select=>&$item){
                if(is_string($item)) {
                    $sql .= " AND ". $this->prefix.$this->table. '.`'.$select ."` = '". $item ."'"; // 修改字段
                }else if(is_array($item)) {
                    $sql .= " AND ". $this->prefix.$this->table. '.`'.$select ."` IN (". implode(',', $item) .")"; // 修改字段
                }else if(is_int($item)) {
                    $sql .= " AND ". $this->prefix.$this->table. '.`'.$select ."` = ".$item; // 修改字段
                }
            }
        }
        return $sql;
    }

    /**
     * 连接
     *
     * @param string $join
     * @param string $table
     * @param string $alias
     * @param string $on
     * @return string
     */
    private function join(string $join, string $table, string $alias, string $on): string
    {
        return " " . $join . " " . $table . " AS ".$alias . " ON ".$on;
    }

    /**
     * 查询排序处理
     *
     * @param array $orderBy
     * @return string
     */
    private function order(array $orderBy): string
    {
        $sql = " ORDER BY";
        if(count($orderBy)) {foreach ($orderBy as $select=>&$order){ $sql .= " `". $select ."` ".$order. ","; }}
        $sql .= " ".$this->prefix.$this->table.".`id` DESC";
        return $sql;
    }

    /**
     * 查询条数处理
     *
     * @param array $limit
     * @return string
     */
    private function limit(array $limit): string
    {
        $sql = '';
        if(count($limit)) { $sql .= " LIMIT ".$limit['offset']. "," .$limit['limit']; }
        return $sql;
    }

    /**
     * 添加
     *
     * @param array $insert
     * @param string $table
     * @return bool
     */
    private function insert(string $table, array $insert): bool
    {
        // 添加字段
        $keys = implode('`,`', array_keys($insert));
        // 添加数据
        $values = implode("','", array_values($insert));
        // 添加SQL
        $sql = "INSERT INTO ".$this->prefix.$table. " (`" .$keys. "`) VALUES ('".$values."')";
        // 执行SQL并返回
        return $this->db::insert($sql);
    }

    /**
     * 添加批量
     *
     * @param string $table
     * @param array $insert
     * @return bool
     */
    private function inserts(string $table, array $insert): bool
    {

        $keys = ''; // 添加字段
        $values = []; // 添加数据
        foreach ($insert as $value) {
            $keys = (bool)strlen($keys) ? $keys : implode('`,`', array_keys($value));
            $values[] = "('" . implode("','", array_values($value))."')";
        }
        // 添加SQL
        $sql = "INSERT INTO ".$this->prefix.$table. " (`" .$keys. "`) VALUES ".implode(",", array_values($values));
        // 执行SQL并返回
        return $this->db::insert($sql);
    }

    /**
     * 修改
     *
     * @param string $table
     * @param array $updated
     * @param int $id
     * @return bool
     */
    private function updated(string $table, array $updated, int $id): bool
    {
        $update = []; // 修改字段
        // 格式化修改字段
        foreach ($updated as $select=>&$item){
            $key = "`". $select ."` = "; // 修改字段
            $value = is_string($item) ? "'". $item ."'" : $item; // 修改字段值
            $update[] =  $key.$value; // 合并字段和值
        }
        $string = implode(',', $update);
        // 修改SQL
        $sql = "UPDATE ". $this->prefix.$table ." SET ".$string." WHERE id = ? AND is_del = 0 LIMIT 1";
        // 执行SQL并返回
        return (bool)$this->db::update($sql, [$id]);
    }

    /**
     * 开启事务
     */
    public function begin(): void
    {
        $this->db::beginTransaction();
    }

    /**
     * 验证事务
     *
     * @param bool $bool
     */
    public function checkBegin(bool $bool): void
    {
        $bool ? $this->db::commit() : $this->db::rollBack();
    }
}
