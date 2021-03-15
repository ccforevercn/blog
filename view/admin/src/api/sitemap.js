/* 网站地图api */
import request from '@/utils/request'

/**
 * 链接
 *
 * @param {*} data
 */
export function GetIndex () {
  return request({
    url: '/sitemap/index',
    method: 'get'
  })
}

/**
 * html
 */
export function PostHtml () {
  return request({
    url: '/sitemap/html',
    method: 'post'
  })
}

/**
 * xml
 */
export function PostXml () {
  return request({
    url: '/sitemap/xml',
    method: 'post'
  })
}

/**
 * tx
 */
export function PostTxt () {
  return request({
    url: '/sitemap/txt',
    method: 'post'
  })
}
