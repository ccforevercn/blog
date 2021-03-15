# 博客网站

#### 新建数据库并配置.env文件配置
###### 数据库名称
`
DB_DATABASE=blog
`
###### 数据库账号
`
DB_USERNAME=root
`
###### 数据库密码
`
DB_PASSWORD=root
`
#### 导入表
`
php artisan migrate
`
#### 导入表默认数据
`
php artisan db:seed
`
#### 后台默认信息
##### 地址：域名+/blog
##### 账号：blogadmin
##### 密码：blogadmin888

