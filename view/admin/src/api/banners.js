/* 轮播图api */
import request from '@/utils/request'

/**
 * 列表
 *
 * @param {*} data
 */
export function GetList (data) {
  return request({
    url: '/banners/list',
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
    url: '/banners/count',
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
    url: '/banners/insert',
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
    url: '/banners/update',
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
    url: '/banners/delete',
    method: 'delete',
    data
  })
}