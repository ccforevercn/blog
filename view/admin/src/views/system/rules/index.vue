<template>
  <div class="app-container">
    <el-row :gutter="24">
      <el-col v-if="treeMenusStatus === true" class="insert-button">
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
      <template slot="empty">暂无规则</template>
      <el-table-column align="center" label="编号" width="95">
        <template slot-scope="scope">
          {{ scope.row.id }}
        </template>
      </el-table-column>
      <el-table-column label="规则名称">
        <template slot-scope="scope">
          {{ scope.row.name }}
        </template>
      </el-table-column>
      <el-table-column label="管理员">
        <template slot-scope="scope">
          {{ scope.row.admin_name }}/{{ scope.row.admin_id }}
        </template>
      </el-table-column>
      <el-table-column align="center" label="添加时间">
        <template slot-scope="scope">
          <i class="el-icon-time" />
          <span>{{ scope.row.add_time }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="操作" width="200">
        <template slot-scope="scope">
          <el-button-group>
            <el-tooltip content="查看菜单" placement="top">
              <el-button
                type="info"
                icon="el-icon-s-operation"
                circle
                @click="menusDialog(scope.$index)"
              />
            </el-tooltip>
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
        <el-form-item label="规则名称">
          <el-input v-model="editInfo.name" />
        </el-form-item>
        <el-form-item>
          <el-tree
            ref="tree"
            :data="treeMenusList"
            :props="treeProps"
            :node-key="treeNodeKey"
            :default-checked-keys="treeDefaultCheckedKeys"
            show-checkbox
            @check-change="treeCheckChange"
          />
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button @click="dialogCancel">取 消</el-button>
        <el-button type="primary" @click="dialogSubmit">确 定</el-button>
      </span>
    </el-dialog>
    <el-dialog
      :title="menusSee.title"
      :visible.sync="menusSee.visible"
      width="30%"
      center
    >
      <el-tree :data="menusSee.list" :props="treeProps" accordion />
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
  GetMenu,
  GetMenus,
} from "@/api/rules";
import { inArray, arrayKey } from "@/utils/array";
import { mapGetters } from "vuex";

export default {
  data() {
    return {
      treeMenusStatus: false,
      where: { page: 1, limit: 6, admin_id: "" },
      list: null,
      count: 0,
      listLoading: false,
      dialogTitle: "",
      dialogVisible: false,
      dialogType: "insert",
      treeNodeKey: "id",
      treeMenusList: [],
      treeDefaultCheckedKeys: [],
      editInfo: { id: 0, name: "", menus_id: "" },
      treeProps: { children: "children", label: "label" },
      menusSee: { title: "", visible: false, list: [] },
    };
  },
  computed: {
    ...mapGetters(["admin"]),
  },
  created() {
    this.where.admin_id = this.admin.id;
    this.getCount();
    this.ruelsMenusList();
  },
  methods: {
    getCount() {
      var that = this;
      GetCount(that.where).then((response) => {
        that.count = response.count;
        that.list = null;
        if(that.count > 0) { that.getList(); }
      });
    },
    deleteDialog(index) {
      // 删除规则
      var that = this;
      var rule = that.list[index];
      that
        .$confirm("您要永久删除【" + rule.name + "】规则吗?", "提示", {
          confirmButtonText: "确定",
          cancelButtonText: "取消",
          type: "warning",
        })
        .then(() => {
          DeleteDelete({ id: rule.id }).then(() => {
            that.getCount();
          });
        })
        .catch(() => {
          that.$message({ type: "info", message: "已取消删除" });
        });
    },
    resetEditInfo() {
      // 重置修改/添加数据
      var that = this;
      that.editInfo.id = 0;
      that.editInfo.name = "";
      that.editInfo.menus_id = "";
    },
    ruelsMenusList() {
      // 当前管理员的所有规则菜单
      var that = this;
      GetMenus().then((response) => {
        that.treeMenusList = response;
        that.treeMenusStatus = true;
      });
    },
    menusDialog(index) {
      // 规则菜单查看
      var that = this;
      var rule = that.list[index];
      that.menusSee.title = "[" + rule.name + "]菜单";
      GetMenu({ id: rule.id }).then((response) => {
        that.menusSee.list = response;
        that.menusSee.visible = true;
      });
    },
    create() {
      // 添加规则
      var that = this;
      that.resetEditInfo();
      that.dialogType = "insert";
      that.dialogTitle = "添加新规则";
      that.dialogVisible = true;
      that.$nextTick(() => {
        that.$refs.tree.setCheckedNodes([]);
        that.treeDefaultCheckedKeys = [];
      });
    },
    treeCheckChangeChildrenPush(data) {
      // treeCheckChange调用 添加子菜单中的编号
      var that = this;
      if (data.hasOwnProperty("children")) {
        for (const index in data.children) {
          if (
            !inArray(
              data.children[index].id,
              that.treeDefaultCheckedKeys,
              "parseint"
            )
          ) {
            that.treeDefaultCheckedKeys.push(data.children[index].id);
          }
          that.treeCheckChangeChildrenPush(data.children[index]);
        }
      }
    },
    treeCheckChangeChildrenSplice(data) {
      // treeCheckChange调用 移除子菜单中的编号
      var that = this;
      if (data.hasOwnProperty("children")) {
        for (const index in data.children) {
          const key = arrayKey(
            data.children[index].id,
            that.treeDefaultCheckedKeys,
            "parseint"
          );
          if (key !== undefined) {
            that.treeDefaultCheckedKeys.splice(key, 1);
          }
          that.treeCheckChangeChildrenSplice(data.children[index]);
        }
      }
    },
    treeCheckChange(data, checked, indeterminate) {
      // 删除/添加 菜单
      var that = this;
      if (!(checked === false && indeterminate === true)) {
        if (checked) {
          /* 节点添加 */
          if (!inArray(data.id, that.treeDefaultCheckedKeys, "parseint")) {
            that.treeDefaultCheckedKeys.push(data.id);
          }
          that.treeCheckChangeChildrenPush(data);
        } else {
          /* 节点取消 */
          const key = arrayKey(
            data.id,
            that.treeDefaultCheckedKeys,
            "parseint"
          );
          if (key !== undefined) {
            that.treeDefaultCheckedKeys.splice(key, 1);
          }
          that.treeCheckChangeChildrenSplice(data);
        }
        that.editInfo.menus_id = that.treeDefaultCheckedKeys.toString();
      }
    },
    editDialog(index) {
      // 修改规则信息
      var that = this;
      that.resetEditInfo();
      var rule = that.list[index];
      that.editInfo.id = rule.id;
      that.editInfo.name = rule.name;
      that.dialogType = "update";
      that.dialogTitle = "修改【" + rule.name + "】规则信息";
      that.editInfo.menus_id = rule.menus_id;
      that.dialogVisible = true;
      that.$nextTick(() => {
        that.$refs.tree.setCheckedNodes([]);
        that.treeDefaultCheckedKeys = rule.menus_id.split(",");
      });
    },
    dialogSubmit() {
      var that = this;
      // 确定添加/修改 规则
      if (that.dialogType === "update") {
        // 修改规则信息
        PutUpdate(that.editInfo).then(() => {
          that.dialogVisible = false;
          that.getList();
        });
      } else {
        // 添加规则信息
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
      // 取消添加/修改规则信息
      var that = this;
      var message = "取消添加规则";
      if (that.dialogType === "update") {
        message = "取消修改规则";
      }
      that.dialogVisible = false;
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
    getList() {
      // 获取规则列表
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
