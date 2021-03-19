<template>
  <div class="app-container">
    <el-row :gutter="24">
      <el-form :model="where" @submit.native.prevent>
        <el-col :span="8">
          <el-form-item label="父级">
            <el-select
              v-model="where.columns_id"
              filterable
              placeholder="请选择父级栏目"
              @change="setWhere"
            >
              <el-option label="全部栏目" value="" />
              <el-option
                v-for="item in columnsList"
                :key="item.id"
                :label="item.name + '/' + item.id"
                :value="item.id"
              />
            </el-select>
          </el-form-item>
        </el-col>
        <el-col :span="6">
          <el-form-item label="首页">
            <el-select
              v-model="where.index"
              placeholder="请选择首页推荐"
              @change="setWhere"
              class="where-select"
            >
              <el-option label="全部" value="" />
              <el-option label="是" value="1" />
              <el-option label="否" value="0" />
            </el-select>
          </el-form-item>
        </el-col>
        <el-col :span="6">
          <el-form-item label="热门">
            <el-select
              v-model="where.hot"
              placeholder="请选择热门推荐"
              @change="setWhere"
              class="where-select"
            >
              <el-option label="全部" value="" />
              <el-option label="是" value="1" />
              <el-option label="否" value="0" />
            </el-select>
          </el-form-item>
        </el-col>
      </el-form>
      <el-col :span="4" class="insert-button">
        <el-button-group>
          <el-button type="primary" plain size="small" @click="create"
            >添加</el-button
          >
          <el-button type="success" plain size="small" @click="getList"
            >刷新</el-button
          >
        </el-button-group>
      </el-col> </el-row
    ><el-table
      v-loading="listLoading"
      :data="list"
      element-loading-text="Loading"
      border
      fit
      highlight-current-row
    >
      <template slot="empty">暂无信息</template>
      <el-table-column label="信息">
        <template slot-scope="scope">
          <span>编号:{{ scope.row.id }}</span
          ><br />
          <span>标题:{{ scope.row.name }}</span
          ><br />
          <span>父级:{{ scope.row.cname }}/{{ scope.row.columns_id }}</span
          ><br />
          <span>关键字:{{ scope.row.keywords }}</span
          ><br />
        </template>
      </el-table-column>
      <el-table-column align="center" label="图片" width="70">
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
      <el-table-column align="center" label="点击量" width="70">
        <template slot-scope="scope"
          ><span
            @click="editNumber(scope.$index, 'click')"
            class="cursor-pointer"
            >{{ scope.row.click }}</span
          ></template
        >
      </el-table-column>
      <el-table-column align="center" label="权重" width="60">
        <template slot-scope="scope"
          ><span
            @click="editNumber(scope.$index, 'weight')"
            class="cursor-pointer"
            >{{ scope.row.weight }}</span
          ></template
        >
      </el-table-column>
      <el-table-column align="center" label="首页" width="60">
        <template slot-scope="scope"
          ><el-tag
            :type="scope.row.index | typeTypeFilter"
            class="cursor-pointer"
            @click="editState(scope.$index, 'index')"
            >{{ scope.row.index | typeFilter }}</el-tag
          ></template
        >
      </el-table-column>
      <el-table-column align="center" label="热门" width="60">
        <template slot-scope="scope"
          ><el-tag
            :type="scope.row.hot | typeTypeFilter"
            class="cursor-pointer"
            @click="editState(scope.$index, 'hot')"
            >{{ scope.row.hot | typeFilter }}</el-tag
          ></template
        >
      </el-table-column>
      <el-table-column align="center" label="时间" width="170">
        <template slot-scope="scope">
          <span>添加:{{ scope.row.add_time }}</span
          ><br />
          <span>修改:{{ scope.row.update_time }}</span></template
        >
      </el-table-column>
      <el-table-column align="center" label="操作" width="180">
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
            <el-tooltip content="内容添加/修改" placement="top">
              <el-button
                type="success"
                icon="el-icon-postcard"
                circle
                @click="editContentDialog(scope.row.id, scope.row.name)"
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
        <el-form-item label="栏目">
          <el-select
            v-model="editInfo.columns_id"
            placeholder="栏目"
            filterable
          >
            <el-option
              v-for="item in columnsList"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="标签">
          <el-select
            v-model="editInfo.tags_id"
            placeholder="标签"
            filterable
            multiple
          >
            <el-option
              v-for="item in tagsList"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="图片">
          <upload-image
            :images="image"
            :name="imageName"
            :path="imagePath"
            @setImagePath="setImagePath"
          />
        </el-form-item>
        <el-form-item label="作者">
          <el-input v-model="editInfo.writer" />
        </el-form-item>
        <el-form-item label="点击量">
          <el-input v-model="editInfo.click" type="number" />
        </el-form-item>
        <el-form-item label="权重">
          <el-input v-model="editInfo.weight" type="number" />
        </el-form-item>
        <el-form-item label="关键字">
          <el-input v-model="editInfo.keywords" />
        </el-form-item>
        <el-form-item label="描述">
          <el-input v-model="editInfo.description" />
        </el-form-item>
        <el-form-item label="首页推荐">
          <el-radio-group v-model="editInfo.index">
            <el-radio label="1">是</el-radio>
            <el-radio label="0">否</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="热门推荐">
          <el-radio-group v-model="editInfo.hot">
            <el-radio label="1">是</el-radio>
            <el-radio label="0">否</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="修改时间">
          <el-date-picker
            v-model="editInfo.update_time"
            type="datetime"
            placeholder="选择修改时间"
          />
        </el-form-item>
        <el-form-item label="页面">
          <el-select v-model="editInfo.page" placeholder="页面" filterable>
            <el-option
              v-for="item in viewsList"
              :key="item.path"
              :label="item.name"
              :value="item.path"
            />
          </el-select>
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button @click="dialogCancel">取 消</el-button>
        <el-button type="primary" @click="dialogSubmit">确 定</el-button>
      </span>
    </el-dialog>
    <el-dialog
      :title="editContent.title"
      :visible.sync="editContent.visibleMarkdown"
      width="60%"
      :before-close="editContentHandleClose"
      class="dialog-visible-content"
    >
      <markdown
        :content-markdown="editContent.content.markdown"
        :name="editContent.contentImageName"
        :path="editContent.contentImagePath"
        @setContent="editContentSet"
      />
      <span slot="footer">
        <el-button type="primary" @click="editContentOrImagesSubmit"
          >确 定</el-button
        >
      </span>
    </el-dialog>
    <el-dialog
      :modal="false"
      :title="editContent.title"
      :visible.sync="editContent.visibleTinyMCE"
      width="60%"
      :before-close="editContentHandleClose"
      class="dialog-visible-content-tiny-mce"
    >
      <tiny-mce-editor
        :content="editContent.content.content"
        :name="editContent.contentImageName"
        :path="editContent.contentImagePath"
        @setContent="editContentSet"
      />
      <span slot="footer">
        <el-button type="primary" @click="editContentOrImagesSubmit"
          >确 定</el-button
        >
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
import { GetColumns } from "@/api/columns";
import { GetTags as TagsList } from "@/api/tags";
import {
  GetList,
  GetCount,
  PostInsert,
  PutUpdate,
  DeleteDelete,
  PostContent,
  PutNumber,
  GetTags,
} from "@/api/messages";
import { GetViews } from "@/api/views";
import UploadImage from "@/components/UploadImage";
import TinyMceEditor from "@/components/TinyMceEditor";
import Markdown from "@/components/Markdown";
import ViewPicture from "@/components/ViewPicture";
import { millisecondToSecond } from "@/utils/time";

export default {
  components: {
    UploadImage,
    TinyMceEditor,
    ViewPicture,
    Markdown,
  },
  filters: {
    typeFilter(index) {
      var indexArr = ["否", "是"];
      return indexArr[index];
    },
    typeTypeFilter(index) {
      var indexTypeArr = ["warning", "success"];
      return indexTypeArr[index];
    },
  },
  data() {
    return {
      imageDialogTableVisible: false,
      imageDialogImage: "",
      imageUrl: "",
      image: "",
      imageName: "messages",
      imagePath: "messages",
      where: {
        page: 1,
        limit: 6,
        columns_id: "",
        index: "",
        hot: "",
        release: "",
      },
      list: null,
      count: 0,
      listLoading: false,
      dialogTitle: "添加",
      dialogVisible: false,
      dialogType: "insert",
      renderType: true,
      editInfo: {
        id: 0,
        name: "",
        columns_id: "",
        tags_id: "0",
        image: "",
        writer: "",
        click: "",
        weight: "",
        keywords: "",
        description: "",
        index: "",
        hot: "",
        update_time: "",
        page: "",
      },
      columnsList: [],
      viewsList: [],
      tagsList: [],
      editContent: {
        title: "修改文章内容",
        visibleTinyMCE: false,
        visibleMarkdown: false,
        contentImageName: "messagescontent",
        contentImagePath: "messagescontent",
        content: {
          id: 0,
          content: "",
          markdown: "",
          type: 0,
        },
      },
    };
  },
  created() {
    this.imageUrl = process.env.VUE_APP_BASE_URL || location.origin;
    this.getColumns();
    this.getViews();
    this.getTagsTotal();
    this.getCount();
  },
  methods: {
    getTagsTotal() {
      // 标签列表
      var that = this;
      TagsList().then((response) => {
        that.tagsList = response;
      });
    },
    getViews() {
      // 视图列表
      var that = this;
      GetViews(2).then((response) => {
        that.viewsList = response;
      });
    },
    getColumns() {
      // 栏目列表
      var that = this;
      GetColumns().then((response) => {
        that.columnsList = response;
      });
    },
    getCount() {
      // 总数
      var that = this;
      GetCount(that.where).then((response) => {
        that.count = response.count;
        that.list = null;
        if(that.count > 0){ that.getList(); }
      });
    },
    getList() {
      // 信息列表
      var that = this;
      that.listLoading = true;
      GetList(that.where).then((response) => {
        that.list = response;
        that.listLoading = false;
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
    editNumber(index, type) {
      // 修改点击量和权重
      var that = this;
      var message = that.list[index];
      that
        .$prompt("请输入数值(最多6位数值)", "提示", {
          confirmButtonText: "确定",
          cancelButtonText: "取消",
          inputValue: message[type].toString(),
          beforeClose: that.dialogBeforeClosed(),
        })
        .then(({ value }) => {
          PutNumber({ id: message.id, type: type, value: value }).then(() => {
            that.getList();
          });
        })
        .catch(() => {
          that.$message({ type: "info", message: res.msg || "取消修改" });
        });
    },
    editState(index, type) {
      // 修改状态
      var that = this;
      var message = that.list[index];
      PutNumber({ id: message.id, type: type, value: !message[type] }).then(
        () => {
          that.getList();
        }
      );
    },
    editContentDialog(id, name) {
      // 添加内容
      var that = this;
      this.getContent(id, function (data) {
        that.editContent.title = "【" + name + "】内容添加/修改";
        if (data.markdown.length > 0) {
          that.editContent.visibleMarkdown = true; // Markdown编辑器
        } else if (data.content.length > 0) {
          that.editContent.visibleTinyMCE = true; // 富文本编辑器
        } else {
          // 提示选择编辑器
          that
            .$confirm(
              "请选择内容编辑器类型，注意：Markdown编辑器需要会Markdown语法",
              "提示",
              {
                closeOnPressEscape: false,
                showClose: false,
                confirmButtonText: "Markdown",
                cancelButtonText: "富文本",
                type: "info",
                center: true,
                roundButton: true,
                closeOnClickModal: false,
              }
            )
            .then(() => {
              // Markdown编辑器
              that.editContent.visibleMarkdown = true;
            })
            .catch((action) => {
              // 富文本编辑器
              that.editContent.visibleTinyMCE = true;
            });
        }
      });
    },
    getContent(id, callback) {
      // 获取信息内容
      var that = this;
      that.editContent.content.id = id;
      that.editContent.content.type = 1;
      that.editContent.content.markdown = "";
      that.editContent.content.content = "";
      PostContent(that.editContent.content).then((response) => {
        that.editContent.content.type = 0;
        that.editContent.content.markdown = response.markdown;
        that.editContent.content.content = response.content;
        callback(response);
      });
    },
    editContentHandleClose(done) {
      // 内容关闭前的回调
      var that = this;
      this.$confirm(
        "您要关闭" +
          that.editContent.title +
          "窗口吗?关闭后如果修改后的内容没有保存就会消失，请先保存后再关闭。",
        "提示",
        {
          confirmButtonText: "已保存，继续关闭",
          cancelButtonText: "未保存，取消关闭",
          type: "warning",
        }
      )
        .then(() => {
          that.editContent.content.id = 0;
          that.editContent.content.markdown = "";
          that.editContent.content.content = "";
          that.editContent.content.type = 0;
          done();
        })
        .catch(() => {
          that.$message({ type: "info", message: "取消关闭" });
        });
    },
    editContentSet(content, render) {
      var that = this;
      if (that.editContent.visibleTinyMCE) {
        // 富文本编辑框
        that.editContent.content.content = render.activeEditor.getContent();
        that.editContent.content.markdown = "";
      } else {
        // 修改内容markdown编辑框的回调
        that.editContent.content.markdown = content;
        that.editContent.content.content = render;
      }
    },
    editContentOrImagesSubmit() {
      // 提交修改文章内容/图片集
      var that = this;
      that.editContent.content.type = 0;
      PostContent(that.editContent.content);
    },
    setWhere() {
      // 条件筛选
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
      // 删除信息
      var that = this;
      var message = that.list[index];
      that
        .$confirm("您要永久删除【" + message.name + "】信息吗?", "提示", {
          confirmButtonText: "确定",
          cancelButtonText: "取消",
          type: "warning",
        })
        .then(() => {
          DeleteDelete({ id: message.id }).then(() => {
            that.getList();
          });
        })
        .catch(() => {
          that.$message({ type: "info", message: "已取消删除" });
        });
    },
    create() {
      // 添加信息
      this.dialogTitle = "添加信息";
      this.editInfo.id = 0;
      this.editInfo.name = "";
      this.editInfo.columns_id = "";
      this.editInfo.tags_id = "";
      this.editInfo.image = "";
      this.image = "";
      this.editInfo.writer = "";
      this.editInfo.click = "";
      this.editInfo.weight = "";
      this.editInfo.keywords = "";
      this.editInfo.description = "";
      this.editInfo.index = "1";
      this.editInfo.hot = "1";
      this.editInfo.update_time = new Date().getTime();
      this.editInfo.page = "";
      this.dialogType = "insert";
      this.dialogVisible = true;
    },
    editDialog(index) {
      // 修改信息
      var that = this;
      var message = that.list[index];
      that.image = "";
      GetTags({ id: message.id }).then((response) => {
        that.editInfo.tags_id = [];
        for (const index in response) {
          that.editInfo.tags_id.push(response[index].id);
        }
        that.editInfo.id = message.id;
        that.editInfo.name = message.name;
        that.editInfo.columns_id = message.columns_id;
        that.editInfo.image = message.image;
        if (message.image.length > 0) {
          that.image = that.imageUrl + message.image;
        }
        that.editInfo.writer = message.writer;
        that.editInfo.click = message.click;
        that.editInfo.weight = message.weight;
        that.editInfo.keywords = message.keywords;
        that.editInfo.description = message.description;
        that.editInfo.index = message.index.toString();
        that.editInfo.hot = message.hot.toString();
        that.editInfo.update_time = new Date().getTime();
        that.editInfo.page = message.page.toString();
        that.dialogTitle = "修改【" + message.name + "】信息";
        that.dialogType = "update";
        that.dialogVisible = true;
      });
    },
    dialogSubmit() {
      // 信息添加修改提交
      var that = this;
      if (that.editInfo.tags_id.length > 0) {
        that.editInfo.tags_id = that.editInfo.tags_id.toString(",");
      } else {
        that.editInfo.tags_id = "";
      }
      that.editInfo.update_time = millisecondToSecond(
        that.editInfo.update_time
      );
      that.editInfo.release_time = millisecondToSecond(
        that.editInfo.release_time
      );
      if (that.dialogType === "update") {
        // 修改栏目 确定
        PutUpdate(that.editInfo).then(() => {
          that.dialogVisible = false;
          that.getList();
        });
      } else {
        // 添加栏目 确定
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
      // 添加/修改信息取消
      var that = this;
      that.dialogVisible = false;
      var message = "取消添加信息";
      if (that.dialogType === "update") {
        message = "取消修改信息";
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
.dialog-visible-content-tiny-mce {
  z-index: 10 !important;
}
.cursor-pointer {
  cursor: pointer;
}
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
.table-image {
  width: 40px;
  height: 40px;
}
.where-select {
  width: 70%;
}
</style>
