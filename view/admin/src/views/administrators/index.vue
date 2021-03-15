/* eslint-disable no-undef */
<template>
  <div class="app-container">
    <el-form class="edit-dialog-form">
      <el-form-item label="账号">
        <el-input v-model="admin.username" :disabled="true" />
      </el-form-item>
      <el-form-item label="密码">
        <el-input v-model="admin.password" placeholder="不填写密码既不修改" />
      </el-form-item>
      <el-form-item label="名称">
        <el-input v-model="admin.real_name" />
      </el-form-item>
      <el-form-item label="邮箱">
        <el-input v-model="admin.email" />
      </el-form-item>
    </el-form>
    <span slot="footer" class="dialog-footer">
      <el-button type="primary" @click="dialogSubmit">修改信息</el-button>
    </span>
  </div>
</template>

<script>
import { getAdmin } from "@/utils/auth";
export default {
  data() {
    return {
      admin: {
        id: 0,
        username: "",
        password: "",
        real_name: "",
        email: "",
      },
    };
  },
  created() {
    this.admin = getAdmin();
    if (this.admin.length === 0) {
      this.$message({ type: "error", message: "参数错误，请重新登陆" });
      this.$store.dispatch("user/logout");
      this.$router.push({ path: "/login" });
    }
    this.admin.password = "";
  },
  methods: {
    dialogSubmit() {
      var that = this;
      SetUpdate({})
        .then((res) => {
          that.$message({ type: "success", message: res.msg || "修改成功" });
          that.dialogVisible = false;
          that.$store.dispatch("user/logout");
          that.$router.push({ path: "/login" });
        })
        .catch((message) => {
          that.$message({ type: "error", message: message });
        });
    },
  },
};
</script>
<style lang="scss" scoped>
.edit-dialog-form {
  margin-top: 20px;
  .el-input {
    width: 80%;
  }
}
.dialog-footer {
  width: 100%;
}
</style>
