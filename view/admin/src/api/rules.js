/* 规则api */
import request from '@/utils/request'

/**
 * 列表
 *
 * @param {*} data
 */
export function GetList (data) {
  return request({
    url: '/rules/list',
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
    url: '/rules/count',
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
    url: '/rules/insert',
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
    url: '/rules/update',
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
    url: '/rules/delete',
    method: 'delete',
    data
  })
}

/**
 * 规则菜单
 * 
 * @returns 
 */
export function GetMenu (data) {
  return request({
    url: '/rules/menu',
    method: 'get',
    params: data
  })
}

/**
 * 菜单
 * 
 * @returns 
 */
export function GetMenus () {
  return request({
    url: '/rules/menus',
    method: 'get'
  })
}

/**
 * 规则
 * 
 * @returns 
 */
export function GetRules () {
  return request({
    url: '/rules/rules',
    method: 'get'
  })
}
