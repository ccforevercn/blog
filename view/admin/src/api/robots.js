/* robots api */
import request from '@/utils/request'

/**
 * 查看
 *
 */
export function GetContent () {
  return request({
    url: '/robots/content',
    method: 'get'
  })
}

/**
 * 修改
 *
 * @param {*} data
 */
export function PutUpdate (data) {
  return request({
    url: '/robots/update',
    method: 'put',
    data
  })
}
