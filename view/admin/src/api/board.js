/* 留言api */
import request from '@/utils/request'

/**
 * 列表
 *
 * @param {*} data
 */
export function GetList (data) {
  return request({
    url: '/board/list',
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
    url: '/board/count',
    method: 'get',
    params: data
  })
}


/**
 * 删除
 *
 * @param {*} data
 */
export function DeleteDelete (data) {
  return request({
    url: '/board/delete',
    method: 'delete',
    data
  })
}