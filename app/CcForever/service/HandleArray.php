<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2021/2/24
 */

namespace App\CcForever\service;

/**
 * 数组处理
 *
 * Class HandleArray
 * @package App\CcForever\service
 */
class HandleArray
{
    /**
     * 获取非空元素
     *
     * @param array $array
     * @param string $default
     * @param array ...$values
     * @return array
     */
    public function getNotNull(array $array, string $default, array ...$values): array
    {
        $format = [];
        foreach ($values as &$value){
            $select =  $value[0];
            $type = strtolower(count($value) === 1 ? $default : $value[1]);
            switch ($type) {
                case 'string':
                    $valueArr = array_key_exists($select, $array) && !is_null($array[$select]) ? [$select => (string)$array[$select]] : [];
                    break;
                case 'int':
                    $valueArr = array_key_exists($select, $array) && !is_null($array[$select]) ? [$select => (int)$array[$select]] : [];
                    break;
                case 'float':
                    $valueArr = array_key_exists($select, $array) && !is_null($array[$select]) ? [$select => (float)$array[$select]] : [];
                    break;
                case 'array':
                    $valueArr = array_key_exists($select, $array) && !is_null($array[$select]) ? [$select => (array)$array[$select]] : [];
                    break;
                default:
                    $valueArr = array_key_exists($select, $array) && !is_null($array[$select]) ? [$select => $array[$select]] : [];
            }
            $format = array_merge($format, $valueArr);
        }
        return $format;
    }
    /**
     * 字段空值
     *
     * @param mixed ...$value
     * @return bool
     */
    public function isNull(...$value): bool
    {
        foreach ($value as &$item){ if(is_null($item)){ return false; }}
        return true;
    }

    /**
     * 数组键值
     *
     * @param array $array
     * @param mixed ...$key
     * @return bool
     */
    public function isKey(array $array, ...$key): bool
    {
        foreach ($key as &$item){ if(!array_key_exists($item, $array)){ return false; }}
        return true;
    }

    /**
     * 字串转数组 字串格式(field:value|field:value|field:value|field:value...)
     *
     * @param string $values
     * @return array
     */
    public function specialStringsToArray(string $values): array
    {
        $format = [];
        $formatCount = 0;
        if(strlen($values)){
            $values = explode('|', $values);
            if(count($values) >= 2){
                foreach ($values as $key=>$value){
                    $formatValue = explode(':', $value);
                    if(count($formatValue) !== 2){
                        $format = [];
                        break;
                    }
                    $format[$formatCount] = $formatValue;
                    $formatCount++;
                }
            }
        }
        return $format;
    }

    /**
     * 移除多余字段
     *
     * @param array $array
     * @param mixed ...$elements
     */
    public function unsetElements(array $array, ...$elements): void
    {
        foreach ($elements as &$element){if(!array_key_exists($element, $array)){$array[$element]=null;unset($array[$element]);}}
    }
}
