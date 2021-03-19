<template>
  <div class="app-container">
    <el-row :gutter="24">
      <el-col :span="24" class="insert-button">
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
      <template slot="empty">暂无轮播图</template>
      <el-table-column align="center" label="编号" width="95">
        <template slot-scope="scope">{{ scope.row.id }}</template>
      </el-table-column>
      <el-table-column label="名称">
        <template slot-scope="scope"
          ><span>{{ scope.row.name }}</span></template
        >
      </el-table-column>
      <el-table-column label="链接">
        <template slot-scope="scope"
          ><el-link :href="scope.row.link" target="_blank">{{
            scope.row.link
          }}</el-link></template
        >
      </el-table-column>
      <el-table-column label="图片">
        <template slot-scope="scope"
          ><span class="cursor-pointer"
            ><el-image
              class="table-image"
              @click="openImage(imageUrl + scope.row.image)"
              :src="imageUrl + scope.row.image"
              fit="scale-down"
            ></el-image></span
        ></template>
      </el-table-column>
      <el-table-column label="权重">
        <template slot-scope="scope">{{ scope.row.weight }}</template>
      </el-table-column>
      <el-table-column align="center" label="时间">
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
        <el-form-item label="链接">
          <el-input v-model="editInfo.link" />
        </el-form-item>
        <el-form-item label="图片">
          <upload-image
            :images="image"
            :name="imageName"
            :path="imagePath"
            @setImagePath="setImagePath"
          />
        </el-form-item>
        <el-form-item label="权重">
          <el-input v-model="editInfo.weight" type="number" />
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button @click="dialogCancel">取 消</el-button>
        <el-button type="primary" @click="dialogSubmit">确 定</el-button>
      </span>
    </el-dialog>
    <view-picture
      :image="imageDialogImage"
      :visible="imageDialogTableVisible"
      @closeViewPicture="closeViewPicture"
    ></view-picture>
  </div>
</template>

<script>
import {
  GetList,
  GetCount,
  PostInsert,
  PutUpdate,
  DeleteDelete,
} from "@/api/banners";
import UploadImage from "@/components/UploadImage";
import ViewPicture from "@/components/ViewPicture";

export default {
  components: {
    ViewPicture,
    UploadImage,
  },
  data() {
    return {
      imageDialogTableVisible: false,
      imageDialogImage: "",
      imageUrl: "",
      image: "",
      imageName: "banners",
      imagePath: "banners",
      where: { page: 1, limit: 6 },
      list: null,
      count: 0,
      listLoading: false,
      dialogTitle: "添加",
      dialogVisible: false,
      dialogType: "insert",
      editInfo: { id: 0, name: "", link: "", image: "", weight: "" },
    };
  },
  created() {
    this.imageUrl = process.env.VUE_APP_BASE_URL || location.origin;
    this.getCount();
  },
  methods: {
    getCount() {
      var that = this;
      GetCount(that.where).then((response) => {
        that.count = response.count;
        that.list = null;
        if(that.count > 0){ that.getList(); }
      });
    },
    closeViewPicture() {
      // 关闭预览图片
      this.imageDialogTableVisible = false;
    },
    openImage(image) {
      // 预览图片
      this.imageDialogTableVisible = true;
      this.imageDialogImage = image;
    },
    setWhereType() {
      // 轮播图类型
      var that = this;
      that.where.page = 1;
      that.getCount();
    },
    setImagePath(imagePath) {
      // 图片上传成功的路径
      var that = this;
      that.image = that.imageUrl + imagePath.path;
      that.editInfo.image = imagePath.path;
    },
    deleteDialog(index) {
      // 删除轮播图
      var that = this;
      var banners = that.list[index];
      that
        .$confirm("您要永久删除【" + banners.name + "】轮播图吗?", "提示", {
          confirmButtonText: "确定",
          cancelButtonText: "取消",
          type: "warning",
        })
        .then(() => {
          DeleteDelete({ id: banners.id }).then(() => {
            that.getList();
          });
        })
        .catch(() => {
          that.$message({ type: "info", message: "已取消删除" });
        });
    },
    create() {
      // 添加轮播图
      this.dialogTitle = "添加轮播图";
      this.editInfo.id = 0;
      this.editInfo.name = "";
      this.editInfo.link = "";
      this.editInfo.image = "";
      this.image = "";
      this.editInfo.weight = "";
      this.dialogType = "insert";
      this.dialogVisible = true;
    },
    editDialog(index) {
      // 修改轮播图
      var banners = this.list[index];
      this.editInfo.id = banners.id;
      this.editInfo.name = banners.name;
      this.editInfo.link = banners.link;
      this.editInfo.image = banners.image;
      this.image = this.imageUrl + banners.image;
      this.editInfo.weight = banners.weight;
      this.dialogTitle = "修改【" + banners.name + "】轮播图信息";
      this.dialogType = "update";
      this.dialogVisible = true;
    },
    dialogSubmit() {
      var that = this;
      if (that.dialogType === "update") {
        // 修改轮播图 确定
        PutUpdate(that.editInfo).then(() => {
          that.dialogVisible = false;
          that.getList();
        });
      } else {
        // 添加轮播图 确定
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
      // 修改轮播图取消
      var that = this;
      that.dialogVisible = false;
      var message = "取消添加轮播图";
      if (that.dialogType === "update") {
        message = "取消修改轮播图";
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
    getList() {
      // 获取轮播图列表
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
.cursor-pointer {
  cursor: pointer;
}
.table-image {
  width: 40px;
  height: 40px;
}
</style>
