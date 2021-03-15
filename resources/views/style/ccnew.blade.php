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
            <div class="detail_main">
                <h3>{{ $message['name'] }}</h3>
                <p class="meta">
                    <span><i class="fa fa-user-circle"></i></span>
                    <span><i class="fa fa-eye"></i>{{ $message['click'] }}</span>
                    <span><i class="fa fa-clock-o"></i>{{ $message['update_time'] }}</span>
                </p>
                <div class="detail_article">@php echo htmlspecialchars_decode($message['content']); @endphp</div>
                <div class="article_footer clearfix">
                    <div class="bdsharebuttonbox fr share">
                        <a href="#" class="bds_more fa fa-share-alt" data-cmd="more"></a>
                        <a href="#" class="bds_weixin fa fa-weixin" data-cmd="weixin" title="分享到微信"></a>
                        <a href="#" class="bds_sqq fa fa-qq" data-cmd="sqq" title="分享到QQ好友"></a>
                        <a href="#" class="bds_tsina fa fa-weibo" data-cmd="tsina" title="分享到新浪微博"></a>
                        <a href="#" class="bds_copy fa fa-copy" data-cmd="copy" title="分享到复制网址"></a>
                        <a href="#" class="bds_mshare fa fa-telegram" data-cmd="mshare" title="分享到一键分享"></a>
                    </div>
                </div>
                @if(count($message['tags']))
                    <div class="article_footer clearfix">
                        <div class="fr tag">标签：
                            @foreach($message['tags'] as $tag)
                                <a href="javascript:void(0);" >{{ $tag }}</a>
                            @endforeach
                        </div>
                    </div>
                @endif
                <div class="post-navigation clearfix">
                    @if(count($message['next']))
                        <div class="post-previous fl">
                            <span>下一篇：</span>
                            <a href='{{ $message['next']['url'] }}' title="{{ $message['next']['name'] }}">{{ $message['next']['name'] }}</a>
                        </div>
                    @endif
                    @if(count($message['pre']))
                        <div class="post-next fr">
                            <span>上一篇：</span>
                            <a href='{{ $message['pre']['url'] }}' title="{{ $message['pre']['name'] }}">{{ $message['pre']['name'] }}</a>
                        </div>
                    @endif
                </div>
            </div>
            @php
                $relevantMessages = (new \App\CcForever\service\Repository())->messages($column['id'], 1, 3, ['update_time' => 'DESC'], 0)
            @endphp
            <div class="related_article">
                <div class="box_title clearfix"><span><i class="icon fa fa-file-text"></i>相关文章</span></div>
                <div class="related_list clearfix">
                    @foreach($relevantMessages as $message)
                        <article class="fl">
                            <div class="related_img">
                                <a href="{{ $message['url'] }}" title="{{ $message['name'] }}">
                                    <img src="{{ $message['image'] }}" alt="{{ $message['name'] }}">
                                </a>
                            </div>
                            <div class="related_detail">
                                <h3><a href="{{ $message['url'] }}" title="{{ $message['name'] }}">{{ $message['name'] }}</a></h3>
                                <div class="meta">
                                    <span><i class="fa fa-eye"></i>{{ $message['click'] }}</span>
                                    <span><i class="fa fa-clock-o"></i>{{ $message['update_time'] }}</span>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
        @include('style.right')
    </div>
</div>
@include('style.footer')
</body>
</html>
