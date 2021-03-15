/* 配置分类api */
import request from '@/utils/request'

/**
 * 列表
 *
 * @param {*} data
 */
export function GetList (data) {
  return request({
    url: '/config/category/list',
    method: 'get',
    params: data
  })
}

/**
 * 总数
 * 
 * @param {*} data 
 * @returns 
 */
export function GetCount (data) {
  return request({
    url: '/config/category/count',
    method: 'get',
    params: data
  })
}

/**
 * 添加
 *
 * @param {*} data
 */
export function PostInsert (data) {
  return request({
    url: '/config/category/insert',
    method: 'post',
    data
  })
}

/**
 * 修改
 *
 * @param {*} data
 */
export function PutUpdate (data) {
  return request({
    url: '/config/category/update',
    method: 'put',
    data
  })
}

/**
 * 删除
 *
 * @param {*} data
 */
export function DeleteDelete (data) {
  return request({
    url: '/config/category/delete',
    method: 'delete',
    data
  })
}

/**
 * 配置分类
 *
 */
export function GetCategory () {
  return request({
    url: '/config/category/category',
    method: 'get'
  })
}
