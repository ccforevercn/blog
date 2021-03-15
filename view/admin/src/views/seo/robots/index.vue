<template>
  <el-row :gutter="24">
    <el-col
      class="robots-refres"
      :xs="{ span: 16, offset: 6 }"
      :sm="{ span: 14, offset: 8 }"
      :md="{ span: 12, offset: 10 }"
      :lg="{ span: 6, offset: 16 }"
      :xl="{ span: 6, offset: 16 }"
    >
      <el-button type="primary" plain size="small" @click="getContent"
        >获取robots内容</el-button
      >
    </el-col>
    <el-col
      class="robots-content"
      :xs="{ span: 16, offset: 4 }"
      :sm="{ span: 16, offset: 4 }"
      :md="{ span: 16, offset: 4 }"
      :lg="{ span: 16, offset: 4 }"
      :xl="{ span: 16, offset: 4 }"
    >
      <el-form>
        <el-input
          v-model="content"
          type="textarea"
          :rows="10"
          placeholder="robots内容"
        />
        <el-form-item class="bottom"
          ><el-button type="primary" @click="update"
            >保存robots内容</el-button
          ></el-form-item
        >
      </el-form>
    </el-col>
  </el-row>
</template>
<script>
import { GetContent, PutUpdate } from "@/api/robots";

export default {
  data() {
    return {
      content: "",
    };
  },
  created() {
    this.getContent();
  },
  methods: {
    getContent() {
      // 获取
      var that = this;
      GetContent().then((response) => {
        that.content = response.content;
      });
    },
    update() {
      // 修改
      PutUpdate({ content: this.content });
    },
  },
};
</script>

<style>
.robots-refres {
  text-align: right;
  margin-top: 10px;
}
.robots-content {
  text-align: left;
  margin-top: 40px;
}
.robots-content .bottom {
  text-align: center;
  margin-top: 20px;
}
</style>
