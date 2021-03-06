/* 公共api */
import request from '@/utils/request'

/**
 * 管理员登陆
 *
 * @param {*} data
 */
export function login (data) {
  return request({
    url: '/login',
    method: 'post',
    data
  })
}

/**
 * 验证码
 */
export function captcha () {
  return request({
    url: '/captcha',
    method: 'get'
  })
}
