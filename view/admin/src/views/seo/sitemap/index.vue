<template>
  <el-row :gutter="24">
    <el-col
      class="robots-content"
      :xs="{ span: 18, offset: 2 }"
      :sm="{ span: 18, offset: 2 }"
      :md="{ span: 18, offset: 2 }"
      :lg="{ span: 18, offset: 2 }"
      :xl="{ span: 18, offset: 2 }"
    >
      <el-form>
        <el-input
          v-model="urls"
          type="textarea"
          :rows="10"
          placeholder="网站链接"
        />
        <el-form-item class="bottom"
          ><el-button type="primary" @click="setHtml"
            >缓存html</el-button
          ></el-form-item
        >
        <el-form-item class="bottom"
          ><el-button type="primary" @click="setXml"
            >缓存xml</el-button
          ></el-form-item
        >
        <el-form-item class="bottom"
          ><el-button type="primary" @click="setTxt"
            >缓存txt</el-button
          ></el-form-item
        >
      </el-form>
    </el-col>
  </el-row>
</template>
<script>
import { GetIndex, PostHtml, PostXml, PostTxt } from "@/api/sitemap";

export default {
  data() {
    return {
      urls: "",
    };
  },
  created() {
    this.getUrls();
  },
  methods: {
    getUrls() {
      // 网站链接获取
      var that = this;
      GetIndex().then((response) => {
        that.urls = response.join("\r\n");
      });
    },
    setHtml() {
      // html缓存
      PostHtml();
    },
    setXml() {
      // xml缓存
      PostXml();
    },
    setTxt() {
      // txt缓存
      PostTxt();
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
