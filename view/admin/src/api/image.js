/* 图片api */
import request from '@/utils/request'

/**
 * 列表
 *
 * @param {*} data
 */
export function GetList (data) {
  return request({
    url: '/image/list',
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
    url: '/image/count',
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
    url: '/image/insert',
    method: 'post',
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
    url: '/image/delete',
    method: 'delete',
    data
  })
}
