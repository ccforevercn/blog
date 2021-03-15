<template>
  <div class="app-container">
    <el-row :gutter="24">
      <el-col class="insert-button">
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
      <template slot="empty">暂无配置分类</template>
      <el-table-column align="center" label="编号" width="95">
        <template slot-scope="scope">{{ scope.row.id }}</template>
      </el-table-column>
      <el-table-column label="名称">
        <template slot-scope="scope"
          ><span>{{ scope.row.name }}</span></template
        >
      </el-table-column>
      <el-table-column label="描述">
        <template slot-scope="scope">{{ scope.row.description }}</template>
      </el-table-column>
      <el-table-column align="center" label="添加时间">
        <template slot-scope="scope"
          ><i class="el-icon-time" /><span>{{
            scope.row.add_time
          }}</span></template
        >
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
        <el-form-item label="描述">
          <el-input v-model="editInfo.description" />
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
} from "@/api/configcategory";

export default {
  data() {
    return {
      where: { page: 1, limit: 6 },
      list: null,
      count: 0,
      listLoading: true,
      dialogTitle: "修改",
      dialogVisible: false,
      dialogType: "insert",
      editInfo: { id: 0, name: "", description: "" },
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
    deleteDialog(index) {
      // 删除配置分类
      var that = this;
      var category = that.list[index];
      that
        .$confirm("您要永久删除【" + category.name + "】配置分类吗?", "提示", {
          confirmButtonText: "确定",
          cancelButtonText: "取消",
          type: "warning",
        })
        .then(() => {
          DeleteDelete({ id: category.id }).then(() => {
            that.getList();
          });
        })
        .catch(() => {
          that.$message({ type: "info", message: "已取消删除" });
        });
    },
    create() {
      // 添加配置分类
      this.dialogTitle = "添加配置分类";
      this.editInfo.id = 0;
      this.editInfo.name = "";
      this.editInfo.description = "";
      this.dialogType = "insert";
      this.dialogVisible = true;
    },
    editDialog(index) {
      // 修改配置分类
      var category = this.list[index];
      this.editInfo.id = category.id;
      this.editInfo.name = category.name;
      this.editInfo.description = category.description;
      this.dialogTitle = "修改【" + category.name + "】配置分类信息";
      this.dialogType = "update";
      this.dialogVisible = true;
    },
    dialogSubmit() {
      var that = this;
      if (that.dialogType === "update") {
        // 修改配置分类 确定
        PutUpdate(that.editInfo).then(() => {
          that.dialogVisible = false;
          that.getList();
        });
      } else {
        // 添加配置分类 确定
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
      // 修改配置分类取消
      var that = this;
      that.dialogVisible = false;
      var message = "取消添加配置分类";
      if (that.dialogType === "update") {
        message = "取消修改配置分类";
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
      that.getCount();
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
    getList() {
      // 获取配置分类列表
      var that = this;
      that.listLoading = true;
      GetList(that.where).then((response) => {
        that.list = response;
        that.listLoading = false;
      });
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
