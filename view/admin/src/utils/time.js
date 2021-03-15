/**
 * 时间格式化
 */
/**
  * 毫秒转秒
  *
  * @param {*} millisecond
  */
export function millisecondToSecond(millisecond) {
  return parseInt(new Date(millisecond).getTime() / 1000)
}
