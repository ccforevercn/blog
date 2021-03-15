<template>
   <el-dialog :title="title" :visible.sync="visible" :show-close="close" :center="center" class="view-picture-dialog" :before-close="closeViewPicture"> 
        <el-image class="view-picture-dialog-image" :src="image" :fit="imageFit"></el-image>
    </el-dialog>
</template>
<script>

export default {
  // 图片预览组件
  name: 'ViewPicture',
  props: {
    image: {
      type: String,
      required: true
    },
    visible: {
      type: Boolean,
      required: true
    }
  },
  data() {
    return {
      title: '',
      close: false,
      center: false,
      imageFit: 'cover'
    }
  },
  created() {
  },
  methods: {
    closeViewPicture(done) {
      // 关闭图片预览窗口
      this.$emit('closeViewPicture')
    }
  }
}
</script>
<style>
.view-picture-dialog{
  width: 100%;
}
.view-picture-dialog .el-dialog__header{
  padding: 0;
}
.view-picture-dialog .el-dialog__body{
  padding: 0;
}
.view-picture-dialog-image{
  width: 100%;
  display: block;
}
</style>
