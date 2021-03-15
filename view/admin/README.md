# admin
后台模板

### 安装插件
```
npm install
```

### 修改配置

#### 修改文件(.env.development)
##### 设置下面参数
```
# 接口根目录
VUE_APP_BASE_API = 
# 网址
VUE_APP_BASE_URL = 
# 网址默认标题
VUE_APP_PAGE_TITLE = 
```
#### 修改文件(.env.production)
##### 设置下面参数
```
# 接口根目录
VUE_APP_BASE_API = 
# 网址
VUE_APP_BASE_URL = 
# 网址默认标题
VUE_APP_PAGE_TITLE = 
```
#### 修改文件(.env.staging)
##### 设置下面参数
```
# 接口根目录
VUE_APP_BASE_API = 
# 网址
VUE_APP_BASE_URL = 
# 网址默认标题
VUE_APP_PAGE_TITLE = 
```
### 查看开发版
```
npm run dev
```
### 打包
```
npm run build:prod
```
## 注意
#### 打包成功后修改disk文件夹内的index.html文件
#### 给引入的js文件和css文件地址前面加上hrkj
#### 压缩disk文件上传到项目网站访问根目录下的hrkj文件夹内
#### disk文件夹内的tinymce文件夹放到项目网站访问根目录下