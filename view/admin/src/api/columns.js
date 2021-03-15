/* 栏目api */
import request from '@/utils/request'

/**
 * 列表
 *
 * @param {*} data
 */
export function GetList (data) {
  return request({
    url: '/columns/list',
    method: 'get',
    params: data
  })
}

/**
 * 总数
 * 
 * @param {*} data 
 */
export function GetCount (data) {
  return request({
    url: '/columns/count',
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
    url: '/columns/insert',
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
    url: '/columns/update',
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
    url: '/columns/delete',
    method: 'delete',
    data
  })
}

/**
 * 内容添加删除查询
 * 
 * @param {*} data 
 */
export function PostContent (data) {
  return request({
    url: '/columns/content',
    method: 'post',
    data
  })
}

/**
 * 栏目
 */
export function GetColumns () {
  return request({
    url: '/columns/columns',
    method: 'get'
  })
}