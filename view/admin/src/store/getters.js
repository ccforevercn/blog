const getters = {
  sidebar: state => state.app.sidebar,
  device: state => state.app.device,
  admin: state => state.user.admin,
  routers: state => state.user.routes
}
export default getters
