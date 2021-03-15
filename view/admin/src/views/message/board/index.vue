<template>
  <div class="app-container">
    <el-row :gutter="24">
      <el-col :span="24" class="insert-button">
        <el-button-group>
          <el-button type="success" plain size="small" @click="getList"
            >刷新</el-button
          >
        </el-button-group>
      </el-col>
    </el-row>
    <el-table
      v-loading="listLoading"
      :data="list"
      element-loading-text="Loading"
      border
      fit
      highlight-current-row
    >
      <template slot="empty">暂无留言</template>
      <el-table-column align="center" label="编号" width="80">
        <template slot-scope="scope">{{ scope.row.id }}</template>
      </el-table-column>
      <el-table-column label="名称">
        <template slot-scope="scope">{{ scope.row.speak }}</template>
      </el-table-column>
      <el-table-column label="内容">
        <template slot-scope="scope">{{ scope.row.content }}</template>
      </el-table-column>
      <el-table-column label="时间">
        <template slot-scope="scope">{{ scope.row.add_time }}</template>
      </el-table-column>
      <el-table-column align="center" label="操作" width="120">
        <template slot-scope="scope">
          <el-button-group>
            <el-tooltip content="删除" placement="top">
              <el-button
                type="success"
                icon="el-icon-delete"
                circle
                @click="deleteDialog(scope.$index)"
              />
            </el-tooltip>
          </el-button-group>
        </template>
      </el-table-column>
    </el-table>
    <el-row :gutter="24">
      <el-col :span="24" class="page-class">
        <el-pagination
          background
          :page-size="where.limit"
          :pager-count="5"
          layout="prev, pager, next"
          :total="count"
          @size-change="pageSizeChange"
          @current-change="pageCurrentChange"
          @prev-click="pagePrevClick"
          @next-click="pageNextClick"
        />
      </el-col>
    </el-row>
  </div>
</template>

<script>
import { GetList, GetCount, DeleteDelete } from "@/api/board";

export default {
  data() {
    return {
      where: { page: 1, limit: 6 },
      list: null,
      count: 0,
      listLoading: true,
    };
  },
  created() {
    this.getCount();
  },
  methods: {
    getCount() {
      // 总数
      var that = this;
      GetCount(that.where).then((response) => {
        that.count = response.count;
        that.getList();
      });
    },
    getList() {
      // 列表
      var that = this;
      that.listLoading = true;
      GetList(that.where).then((response) => {
        that.list = response;
        that.listLoading = false;
      });
    },
    deleteDialog(index) {
      // 删除标签
      var that = this;
      var board = that.list[index];
      that
        .$confirm("您要永久删除【" + board.speak + "】留言吗?", "提示", {
          confirmButtonText: "确定",
          cancelButtonText: "取消",
          type: "warning",
        })
        .then(() => {
          DeleteDelete({ id: board.id }).then(() => {
            that.getList();
          });
        })
        .catch(() => {
          that.$message({ type: "info", message: "已取消删除" });
        });
    },
    pageSizeChange() {
      // 分页修改每页条数触发
      console.log("pageSizeChange");
    },
    pageCurrentChange(page) {
      // 跳转页面触发
      var that = this;
      that.where.page = page;
      that.getList();
    },
    pagePrevClick(page) {
      // 上一页触发
      console.log(page);
      console.log("pagePrevClick");
    },
    pageNextClick(page) {
      // 下一页触发
      console.log(page);
      console.log("pageNextClick");
    },
  },
};
</script>
<style lang="scss" scoped>
.page-class {
  text-align: center;
  margin-top: 10px;
}
.edit-dialog-form {
  margin-top: 20px;
  .el-input {
    width: 80%;
  }
}
.insert-button {
  text-align: right;
  margin-bottom: 10px;
}
</style>
