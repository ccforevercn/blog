import axios from 'axios'
import { Message } from 'element-ui'
import { getToken } from '@/utils/auth'
import store from '@/store'

var url = process.env.VUE_APP_BASE_URL || location.origin;
var base = url + process.env.VUE_APP_BASE_API;
const service = axios.create({
  baseURL: base,
  timeout: 5000
})

service.interceptors.request.use(
  config => {
    config.headers['Authorization'] = 'bearer ' + getToken()
    return config
  },
  error => {
    return Promise.reject(error)
  }
)

service.interceptors.response.use(
  response => {
    const res = response.data
    if (typeof res === 'object') {
      switch (res.code) {
        case 200:
          return res.data;
        case 206:
          Message({
            message: res.message,
            type: 'warning',
            duration: 1500
          })
          return res.data;
        case 400:
          Message({
            message: res.message,
            type: 'error',
            duration: 3 * 1000
          })
          return Promise.reject(res.message);
        case 401:
          Message({
            message: res.message,
            type: 'error',
            duration: 3 * 1000
          })
          store.dispatch('user/logout')
          var timer = setTimeout(function () {
            clearTimeout(timer)
            location.reload()
          }, 3000)
      }
    } else {
      return Promise.reject(String(res))
    }
  },
  error => {
    const result = error.response
    var message = result.data.message ? result.data.message : "请求失败"
    Message({
      message: message,
      type: 'error',
      duration: 5 * 1000
    })
    return Promise.reject(message)
  }
)

export default service
