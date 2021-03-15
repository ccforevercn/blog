<template>
  <div class="dashboard-container dashboard">
    <div class="top">欢迎管理员： {{ admin.real_name }}</div>
    <div ref="echarts" class="echarts" />
  </div>
</template>

<script>
import { getAdmin } from "@/utils/auth";

export default {
  name: "Dashboard",
  data() {
    return {
      admin: [],
      time: 0,
      timer: 0,
    };
  },
  mounted() {
    this.admin = getAdmin();
  },
  destroyed() {},
  methods: {},
};
</script>

<style lang="scss" scoped>
.dashboard {
  height: 800px;
  .top {
    height: 100px;
    text-align: center;
    line-height: 100px;
    color: #666fff;
  }
  &-container {
    margin: 30px;
  }
  &-text {
    font-size: 30px;
    line-height: 46px;
  }
}
</style>
