/* 菜单api */
import request from '@/utils/request'

/**
 * 列表
 *
 * @param {*} data
 */
export function GetList (data) {
  return request({
    url: '/menus/list',
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
    url: '/menus/count',
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
    url: '/menus/insert',
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
    url: '/menus/update',
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
    url: '/menus/delete',
    method: 'delete',
    data
  })
}

/**
 * 菜单
 *
 * @param {*} data
 */
export function GetMenus (data) {
  return request({
    url: '/menus/menus',
    method: 'get',
    params: data
  })
}

/**
 * 侧边栏
 *
 * @param {*} data
 */
export function GetSidebar (data) {
  return request({
    url: '/menus/sidebar',
    method: 'get',
    params: data
  })
}
