<template>
  <el-row>
    <el-row :gutter="24" class="index-cache">
      <el-col
        :xs="{ span: 16, offset: 6 }"
        :sm="{ span: 14, offset: 8 }"
        :md="{ span: 12, offset: 10 }"
        :lg="{ span: 6, offset: 16 }"
        :xl="{ span: 6, offset: 16 }"
      >
        <el-button-group>
          <el-button type="primary" plain size="small" @click="cacheIndex"
            >首页</el-button
          >
          <el-button type="primary" plain size="small" @click="cacheSearch"
            >搜索页</el-button
          >
        </el-button-group>
      </el-col>
    </el-row>
    <el-row :gutter="24" class="column-cache">
      <el-col
        :xs="{ span: 20, offset: 4 }"
        :sm="{ span: 16, offset: 8 }"
        :md="{ span: 14, offset: 8 }"
        :lg="{ span: 10, offset: 8 }"
        :xl="{ span: 8, offset: 8 }"
      >
        <el-form>
          <el-form-item label="栏目缓存">
            <el-select
              v-model="columnId"
              filterable
              placeholder="请选择缓存的栏目"
            >
              <el-option label="全部栏目" value="0" />
              <el-option
                v-for="item in columnsList"
                :key="item.id"
                :label="item.name + '/' + item.id"
                :value="item.id"
              />
            </el-select>
          </el-form-item>
          <el-form-item class="cache-list">
            <el-button-group>
              <el-button class="cache-item" type="primary" @click="cacheColumns"
                >缓存</el-button
              >
            </el-button-group>
          </el-form-item>
        </el-form>
      </el-col>
    </el-row>
    <el-row :gutter="24" class="message-cache">
      <el-col
        :xs="{ span: 20, offset: 4 }"
        :sm="{ span: 16, offset: 8 }"
        :md="{ span: 14, offset: 8 }"
        :lg="{ span: 10, offset: 8 }"
        :xl="{ span: 8, offset: 8 }"
      >
        <el-form>
          <el-form-item label="信息缓存">
            <el-select
              v-model="messageColumnId"
              filterable
              placeholder="请选择缓存的栏目"
            >
              <el-option label="全部栏目" value="0" />
              <el-option
                v-for="item in columnsList"
                :key="item.id"
                :label="item.name + '/' + item.id"
                :value="item.id"
              />
            </el-select>
          </el-form-item>
          <el-form-item class="cache-list">
            <el-button-group>
              <el-button class="cache-item" type="primary" @click="cacheMessage"
                >缓存</el-button
              >
            </el-button-group>
          </el-form-item>
        </el-form>
      </el-col>
    </el-row>
  </el-row>
</template>

<script>
import { GetColumns } from "@/api/columns";
import { PostIndex, PostColumns, PostMessage, PostSearch } from "@/api/cache";

export default {
  data() {
    return {
      columnsList: [],
      columnId: "0",
      messageColumnId: "0",
      data: {
        id: 0,
      },
    };
  },
  created() {
    this.getColumns();
  },
  methods: {
    getColumns() {
      // 栏目
      var that = this;
      GetColumns().then((response) => {
        that.columnsList = response;
      });
    },
    cacheIndex() {
      // 首页
      PostIndex();
    },
    cacheSearch() {
      // 搜索页
      PostSearch();
    },
    cacheColumns() {
      // 缓存栏目
      this.data.id = Number(this.columnId);
      PostColumns(this.data);
    },
    cacheMessage() {
      // 缓存信息
      this.data.id = Number(this.messageColumnId);
      PostMessage(this.data);
    },
  },
};
</script>

<style>
.index-cache {
  text-align: right;
  margin-top: 10px;
}
.column-cache {
  margin: 20px 0;
}
.message-cache {
  margin: 20px 0;
}
.cache-list {
  width: 260px;
}
.cache-item {
  width: 260px;
}
</style>
