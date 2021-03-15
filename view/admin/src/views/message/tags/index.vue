<template>
  <div class="app-container">
    <el-row :gutter="24">
      <el-form :model="where" @submit.native.prevent>
        <el-col :span="16">
          <el-form-item label="标签状态">
            <el-select
              v-model="where.status"
              placeholder="请选择标签状态"
              @change="setWhere"
            >
              <el-option label="全部" value="" />
              <el-option label="隐藏" value="0" />
              <el-option label="显示" value="1" />
            </el-select>
          </el-form-item>
        </el-col>
      </el-form>
      <el-col :span="8" class="insert-button">
        <el-button-group>
          <el-button type="primary" plain size="small" @click="create"
            >添加</el-button
          >
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
      <template slot="empty">暂无标签</template>
      <el-table-column align="center" label="编号" width="80">
        <template slot-scope="scope">{{ scope.row.id }}</template>
      </el-table-column>
      <el-table-column label="名称">
        <template slot-scope="scope">{{ scope.row.name }}</template>
      </el-table-column>
      <el-table-column align="center" label="状态">
        <template slot-scope="scope"
          ><el-tag :type="scope.row.status | statusTypeFilter" class="cursor">{{
            scope.row.status | statusFilter
          }}</el-tag></template
        >
      </el-table-column>
      <el-table-column label="时间">
        <template slot-scope="scope">{{ scope.row.add_time }}</template>
      </el-table-column>
      <el-table-column align="center" label="操作" width="120">
        <template slot-scope="scope">
          <el-button-group>
            <el-tooltip content="修改" placement="top">
              <el-button
                type="primary"
                icon="el-icon-edit"
                circle
                @click="editDialog(scope.$index)"
              />
            </el-tooltip>
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
    <el-dialog
      :title="dialogTitle"
      :visible.sync="dialogVisible"
      :before-close="dialogBeforeClosed"
      width="60%"
      center
    >
      <el-form class="edit-dialog-form">
        <el-form-item label="名称">
          <el-input v-model="editInfo.name" />
        </el-form-item>
        <el-form-item label="状态">
          <el-radio-group v-model="editInfo.status">
            <el-radio label="1">展示</el-radio>
            <el-radio label="0">隐藏</el-radio>
          </el-radio-group>
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button @click="dialogCancel">取 消</el-button>
        <el-button type="primary" @click="dialogSubmit">确 定</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import {
  GetList,
  GetCount,
  PostInsert,
  PutUpdate,
  DeleteDelete,
} from "@/api/tags";

export default {
  filters: {
    statusFilter(status) {
      var statusArr = ["隐藏", "显示"];
      return statusArr[status];
    },
    statusTypeFilter(status) {
      var statusArr = ["danger", "success"];
      return statusArr[status];
    },
  },
  data() {
    return {
      where: { page: 1, limit: 6, status: "" },
      list: null,
      count: 0,
      listLoading: false,
      dialogTitle: "添加",
      dialogVisible: false,
      dialogType: "insert",
      editInfo: { id: 0, name: "", status: "" },
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
        if(that.count > 0){ that.getList(); }
      });
    },
    getList() {
      // 获取标签列表
      var that = this;
      that.listLoading = true;
      GetList(that.where).then((response) => {
        that.list = response;
        that.listLoading = false;
      });
    },
    setWhere() {
      // 条件筛选
      var that = this;
      that.where.page = 1;
      that.getCount();
    },
    deleteDialog(index) {
      // 删除标签
      var that = this;
      var tag = that.list[index];
      that
        .$confirm("您要永久删除【" + tag.name + "】标签吗?", "提示", {
          confirmButtonText: "确定",
          cancelButtonText: "取消",
          type: "warning",
        })
        .then(() => {
          DeleteDelete({ id: tag.id }).then(() => {
            that.getList();
          });
        })
        .catch(() => {
          that.$message({ type: "info", message: "已取消删除" });
        });
    },
    create() {
      // 添加标签
      this.dialogTitle = "添加标签";
      this.editInfo.id = 0;
      this.editInfo.name = "";
      this.editInfo.status = "0";
      this.dialogType = "insert";
      this.dialogVisible = true;
    },
    editDialog(index) {
      // 修改标签
      var tag = this.list[index];
      this.editInfo.id = tag.id;
      this.editInfo.name = tag.name;
      this.editInfo.status = tag.status.toString();
      this.dialogTitle = "修改【" + tag.name + "】标签信息";
      this.dialogType = "update";
      this.dialogVisible = true;
    },
    dialogSubmit() {
      var that = this;
      if (that.dialogType === "update") {
        // 修改标签 确定
        PutUpdate(that.editInfo).then(() => {
          that.dialogVisible = false;
          that.getList();
        });
      } else {
        // 添加标签 确定
        PostInsert(that.editInfo).then(() => {
          that.dialogVisible = false;
          that.getList();
        });
      }
    },
    dialogBeforeClosed(done) {
      // 修改、添加窗口未点击取消和确定按钮关闭回调
      var that = this;
      that
        .$confirm(
          "您要当前窗口吗?关闭后没有保存的数据就会消失,请先保存后再关闭。",
          "提示",
          {
            confirmButtonText: "已保存，继续关闭",
            cancelButtonText: "未保存，取消关闭",
            type: "warning",
          }
        )
        .then(() => {
          done();
        })
        .catch(() => {
          that.$message({ type: "info", message: "取消关闭" });
        });
    },
    dialogCancel() {
      // 修改标签取消
      var that = this;
      that.dialogVisible = false;
      var message = "取消添加标签";
      if (that.dialogType === "update") {
        message = "取消修改标签";
      }
      that.$message({ type: "warning", message: message });
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
  margin-top: 10px;
}
</style>
