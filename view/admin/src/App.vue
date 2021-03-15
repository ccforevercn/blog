<template>
  <div id="app">
    <router-view />
  </div>
</template>

<script>
import router from "./router";
import { getToken } from "./utils/auth";
export default {
  name: "App",
  beforeCreate() {
    var that = this;
    if (getToken() !== null) {
      that.$store.dispatch("user/sidebar").then((asyncRoutes) => {
        router.addRoutes(asyncRoutes);
      });
    }
  },
};
</script>
