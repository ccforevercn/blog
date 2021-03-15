/* 信息api */
import request from '@/utils/request'

/**
 * 列表
 *
 * @param {*} data
 */
export function GetList (data) {
  return request({
    url: '/messages/list',
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
    url: '/messages/count',
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
    url: '/messages/insert',
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
    url: '/messages/update',
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
    url: '/messages/delete',
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
    url: '/messages/content',
    method: 'post',
    data
  })
}

/**
 * 点击量、权重、首页推荐、热门推荐修改
 *
 * @param {*} data
 */
export function PutNumber (data) {
  return request({
    url: '/messages/number',
    method: 'put',
    data
  })
}


/**
 * 标签
 *
 * @param {*} data
 */
export function GetTags (data) {
  return request({
    url: '/messages/tags',
    method: 'get',
    params: data
  })
}
