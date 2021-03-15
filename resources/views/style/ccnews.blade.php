<!DOCTYPE html>
<html lang="zh-CN">
<head>
    @include('style.style')
</head>
<body>
@include('style.header')
<div class="content">
    <div class="container clearfix">
        <nav class="breadcrumb">@php echo $crumbs; @endphp</nav>
        <div class="leftmain fl">
            <div class="archive-head">
                <div class="headline"><i class="fa fa-clone"></i><span>分类：</span><p>{{ $firstColumn['name'] }}</p></div>
                <div class="archive-description"><p>{{ $firstColumn['description'] }}</p></div>
            </div>
            <div class="newlist">
                @foreach($messages as $message)
                    <li class="clearfix">
                        <div class="l-img fl">
                            <a class="new_img" href="{{ $message['url'] }}" title="{{ $message['name'] }}">
                                <img src="{{ $message['url'] }}" alt="{{ $message['name'] }}">
                            </a>
                            <a class="tag hidden-sm-md-lg" href="{{ $message['url'] }}" title="{{ $message['name'] }}">{{ $message['cname'] }}</a>
                        </div>
                        <div class="main_news fr">
                            <h3><a href="{{ $message['url'] }}" title="{{ $message['name'] }}">{{ $message['name'] }}</a></h3>
                            <p class="main_article m-multi-ellipsis">{{ $message['description'] }}</p>
                            <p class="meta"> <span class="hidden-sm-md-lg"><i class="fa fa-user-circle"></i></span><span><i class="fa fa-eye"></i>{{ $message['click'] }}</span><span><i class="fa fa-clock-o"></i>{{ $message['update_time'] }}</span> </p>
                        </div>
                    </li>
                @endforeach
            </div>
            <div class="pages">
                @php echo (new \App\CcForever\service\Repository())->page($column['id'], $page); @endphp
            </div>
        </div>
        @include('style.right')
    </div>
</div>
@include('style.footer')
</body>
</html>
