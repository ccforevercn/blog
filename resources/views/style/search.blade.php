<!DOCTYPE html>
<html lang="zh-CN">
<head>
    @include('style.style')
</head>
<body>
@include('style.header')
<div class="content">
    <div class="container clearfix">
        <nav class="breadcrumb"><a href="/" title="首页">首页</a>>搜索页</nav>
        <div class="leftmain fl">
            <div class="archive-head"><div class="headline"><i class="fa fa-clone"></i><span>搜索：</span><p id="search"></p></div></div>
            <div class="newlist" id="search-list"></div>
        </div>
        @include('style.right')
    </div>
</div>
@include('style.footer')
</body>
</html>
