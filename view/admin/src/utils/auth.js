import sessionStorage from 'sessionstorage'

const token = 'admin_token'

const status = 'admin_status'

const admin = 'admin'

/**
 * 获取秘钥
 */
export function getToken () {
  return sessionStorage.getItem(token);
}

/**
 * 设置秘钥
 * 
 * @param {*} secret 
 */
export function setToken (secret) {
  return sessionStorage.setItem(token, secret)
}

/**
 * 删除秘钥
 */
export function removeToken () {
  return sessionStorage.removeItem(token)
}

/**
 * 获取状态
 */
export function getStatus () {
  if (sessionStorage.getItem(status) === null) { return 'false' }
  return sessionStorage.getItem(status)
}

/**
 * 设置状态
 * 
 * @param {*} state 
 */
export function setStatus (state) {
  return sessionStorage.setItem(status, state)
}

/**
 * 删除状态
 */
export function removetStatus () {
  return sessionStorage.removeItem(status)
}

/**
 * 获取管理员信息
 */
export function getAdmin () {
  if (sessionStorage.getItem(admin) === null) { return ''; }
  return JSON.parse(sessionStorage.getItem(admin));
}

/**
 * 设置管理员信息
 * 
 * @param {*} message 
 */
export function setAdmin (message) {
  return sessionStorage.setItem(admin, message)
}

/**
 * 删除管理员信息
 */
export function removeAdmin () {
  return sessionStorage.removeItem(admin)
}