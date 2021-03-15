/* 视图api */
import request from '@/utils/request'

/**
 * 列表
 *
 * @param {*} data
 */
export function GetList (data) {
  return request({
    url: '/views/list',
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
    url: '/views/count',
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
    url: '/views/insert',
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
    url: '/views/update',
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
    url: '/views/delete',
    method: 'delete',
    data
  })
}

/**
 * 标签
 * 
 * @param {*} type 
 */
export function GetViews (type) {
  return request({
    url: '/views/views',
    method: 'get',
    params: { type: type }
  })
}
