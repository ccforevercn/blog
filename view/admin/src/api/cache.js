/* 缓存api */
import request from '@/utils/request'

/**
 * 首页
 *
 * @param {*} data
 */
export function PostIndex (data) {
  return request({
    url: '/cache/index',
    method: 'post',
    data
  })
}

/**
 * 栏目
 *
 * @param {*} data
 */
export function PostColumns (data) {
  return request({
    url: '/cache/columns',
    method: 'post',
    data
  })
}

/**
 * 信息
 *
 * @param {*} data
 */
export function PostMessage (data) {
  return request({
    url: '/cache/message',
    method: 'post',
    data
  })
}

/**
 * 搜索
 *
 * @param {*} data
 */
export function PostSearch (data) {
  return request({
    url: '/cache/search',
    method: 'post',
    data
  })
}
