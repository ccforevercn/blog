/* 配置信息api */
import request from '@/utils/request'

/**
 * 列表
 *
 * @param {*} data
 */
export function GetList (data) {
  return request({
    url: '/config/message/list',
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
    url: '/config/message/count',
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
    url: '/config/message/insert',
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
    url: '/config/message/update',
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
    url: '/config/message/delete',
    method: 'delete',
    data
  })
}