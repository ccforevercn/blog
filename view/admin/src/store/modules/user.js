import { login } from '@/api/public'
import { setToken, removeToken, setStatus, removetStatus, setAdmin, removeAdmin } from '@/utils/auth'
import { resetRouter, asyncRoutes, constantRoutes } from '@/router'
import { GetSidebar } from '@/api/menus'
import Layout from '@/layout'

export function formatMenus (routes, data) {
  data.forEach(item => {
    const menu = {}
    if (item.top) {
      menu.path = item.page
      menu.component = Layout
      menu.children = []
      menu.alwaysShow = true
      menu.redirect = 'noRedirect'
      menu.name = item.name
      menu.meta = { title: item.meta.title, icon: item.meta.icon }
    } else {
      menu.path = item.page
      menu.component = resolve => require([`@/views${item.page}/index`], resolve)
      menu.children = []
      menu.name = item.name
      menu.meta = { title: item.meta.title, icon: item.meta.icon }
    }
    if (item.children) {
      formatMenus(menu.children, item.children)
    }
    routes.push(menu)
  })
}

const state = {
  routes: [],
  admin: [],
}

const mutations = {
  SET_ROUTES: (state, routes) => {
    routes = routes.concat({ path: '*', redirect: '/404', hidden: true })
    state.routes = constantRoutes.concat(routes)
  },
  SET_ADMIN: (state, admin) => {
    state.admin = admin
  }
}

const actions = {
  sidebar ({ commit }) {
    return new Promise((resolve, reject) => {
      const loadMenuData = []
      GetSidebar().then(sidebar => {
        Object.assign(loadMenuData, sidebar)
        formatMenus(asyncRoutes, loadMenuData)
        commit('SET_ROUTES', asyncRoutes)
        resolve(asyncRoutes);
      }).catch(error => {
        reject(error)
      })
    })
  },
  login ({ commit }, userInfo) {
    const { username, password, captcha, key } = userInfo
    return new Promise((resolve, reject) => {
      login({ username: username.trim(), password: password, captcha: captcha, key: key }).then(data => {
        setToken(data.token);
        setAdmin(JSON.stringify(data));
        setStatus('true');
        commit('SET_ADMIN', data);
        resolve();
      }).catch(error => {
        reject(error)
      })
    })
  },
  logout ({ }) {
    return new Promise(resolve => {
      removeToken();
      removetStatus();
      removeAdmin();
      resetRouter();
      resolve();
    }).catch(error => {
      reject(error);
    })
  },
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}

