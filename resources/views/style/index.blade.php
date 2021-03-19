<!DOCTYPE html>
<html lang="zh-CN">
<head>
    @include('style.style')
    <script src="/style/js/js.js" type="text/javascript"></script>
</head>
<body>
@include('style.header')
<main class="content">
    <div class="container clearfix">
        @if(count($banners))
            <div class="slider_wraper clearfix">
                    <div class="swiper-container fl">
                        <div class="swiper-wrapper">
                            @foreach($banners as $banner)
                                @if($loop->index < 3)
                                    <div class="swiper-slide">
                                        <a href="{{ $banner['link'] }}" title="{{ $banner['name'] }}"><img src="{{ $banner['image'] }}" alt="{{ $banner['name'] }}" width="690" height="365"></a>
                                        <div class="swiper_des"><h3><a href="{{ $banner['link'] }}"  title="{{ $banner['name'] }}">{{ $banner['name'] }}</a></h3></div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="swiper-pagination swiper-pagination-white"></div>
                        <div class="swiper-button-next swiper-button-white"></div>
                        <div class="swiper-button-prev swiper-button-white"></div>
                    </div>
                    <div class="slider_side fr">
                        <ul>
                            @foreach($banners as $banner)
                                @if($loop->index > 2 && $loop->index < 7)
                                    <li>
                                        <a href="{{ $banner['url'] }}" title="{{ $banner['name'] }}">
                                            <span class="mask"></span><img src="{{ $banner['image'] }}" alt="{{ $banner['name'] }}"></a>
                                        <div class="side_pic_des">
                                            <h3><a href="{{ $banner['url'] }}" title="{{ $banner['name'] }}">{{ $banner['name'] }}</a></h3>
                                        </div>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
            </div>
        @endif
        <div class="leftmain fl">
            @php
                $marvellousMessages = (new \App\CcForever\service\Repository())->messages(0, 1, 3, ['click' => 'DESC'], 1)
            @endphp
            @if(count($marvellousMessages))
                <div class="recommend clearfix">
                    <div class="box_title clearfix"><span><i class="icon fa fa-bullhorn"></i>精彩推荐</span> </div>
                    @foreach($marvellousMessages as $message)
                        <article class="rec_article fl">
                            <a href="{{ $message['url'] }}" title="{{ $message['name'] }}">
                                <img src="{{ $message['image'] }}" alt="{{ $message['name'] }}">
                            </a>
                            <div class="rec_detail">
                                <header class="clearfix">
                                    <h3 ><a href="{{ $message['url'] }}" title="{{ $message['name'] }}">{{ $message['name'] }}</a></h3>
                                </header>
                                <p class="note">  {{ (new \App\CcForever\service\Util())->cutString($message['description'], 0, 12, true, '...')  }}</p>
                                <p class="meta">
                                    <span><i class="fa fa-eye"></i>{{ $message['click'] }}</span>
                                    <span><i class="fa fa-clock-o"></i>{{ $message['update_time'] }}</span>
                                </p>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
            @php
                $newsMessages = (new \App\CcForever\service\Repository())->messages(0, 1, 5, ['update_time' => 'DESC'], 1)
            @endphp
            @if(count($newsMessages))
                <div class="newlist">
                    <div class="box_title clearfix"> <span><i class="icon time_icon fa fa-clock-o"></i>最新文章</span> </div>
                    <ol>
                        @foreach($newsMessages as $message)
                            <li class="clearfix">
                                <div class="l-img fl">
                                    <a class="new_img" href="{{ $message['url'] }}" title="{{ $message['name'] }}">
                                        <img src="{{ $message['image'] }}" alt="{{ $message['name'] }}">
                                    </a>
                                    <a class="tag hidden-sm-md-lg" href="{{ $message['name'] }}" title="{{ $message['name'] }}">{{ $message['cname'] }}</a>
                                </div>
                                <div class="main_news fr">
                                    <h3><a href="{{ $message['url'] }}" title="{{ $message['name'] }}">{{ $message['name'] }}</a></h3>
                                    <p class="main_article m-multi-ellipsis">  {{ (new \App\CcForever\service\Util())->cutString($message['description'], 0, 12, true, '...')  }}</p>
                                    <p class="meta">
                                        <span><i class="fa fa-eye"></i>{{ $message['click'] }}</span>
                                        <span><i class="fa fa-clock-o"></i>{{ $message['update_time'] }}</span>
                                    </p>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </div>
            @endif
            @php
                $column = (new \App\CcForever\service\Repository())->column(6, ['name']);
                $messages = (new \App\CcForever\service\Repository())->messages(6, 1, 6, ['update_time' => 'DESC'], 1)
            @endphp
            @if(count($column))
                <div class="stars clearfix">
                    <div class="box_title clearfix"><span><i class="icon news_icon fa fa-file-text"></i>{{ $column['name'] }}</span> <a class="fr" href="{{ $column['url'] }}" title="{{ $column['name'] }}">更多<i class="fa fa-angle-right"></i></a></div>
                    @foreach($messages as $message)
                        <article class="stars_article fl">
                            <div class="stars_img">
                                <a href="{{ $message['url'] }}" title="{{ $message['name'] }}">
                                    <img src="{{ $message['image'] }}" alt="{{ $message['name'] }}">
                                </a>
                                <span><i class="fa fa-video-camera"></i></span>
                            </div>
                            <div class="stars_detail">
                                <header class="clearfix"><h3><a href="{{ $message['url'] }}" title="{{ $message['name'] }}">{{ $message['name'] }}</a></h3></header>
                                <p class="note">  {{ (new \App\CcForever\service\Util())->cutString($message['description'], 0, 12, true, '...')  }}</p>
                                <p class="read_all"><a href="{{ $message['url'] }}" title="{{ $message['name'] }}">阅读全文</a></p>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
            @php
                $column = (new \App\CcForever\service\Repository())->column(2, ['name']);
                $messages = (new \App\CcForever\service\Repository())->messages(2, 1, 2, ['update_time' => 'DESC'], 1)
            @endphp
            @if(count($column))
                <div class="lastnews">
                    <div class="box_title clearfix"> <span><i class="icon fa fa-fire"></i>{{ $column['name'] }}</span> <a class="fr" href="{{ $column['url'] }}" title="{{ $column['name'] }}">更多<i class="fa fa-angle-right"></i></a> </div>
                    @foreach($messages as $message)
                        <article class="lastnews_article clearfix">
                            <div class="lastnews_img fl">
                                <a href="{{ $message['url'] }}" title="{{ $message['name'] }}">
                                    <img src="{{ $message['image'] }}" alt="{{ $message['name'] }}"></a> </div>
                            <div class="lastnews_detail fr">
                                <header class="clearfix"><h3><a href="{{ $message['url'] }}" title="{{ $message['name'] }}">{{ $message['name'] }}</a></h3></header>
                                <p class="meta">
                                    <span><i class="fa fa-eye"></i>{{ $message['click'] }}</span>
                                    <span><i class="fa fa-clock-o"></i>{{ $message['update_time'] }}</span>
                                </p>
                                <p class="note m-multi-ellipsis">  {{ (new \App\CcForever\service\Util())->cutString($message['description'], 0, 12, true, '...')  }}</p>
                                <p class="read_all hidden-sm-md-lg"><a href="{{ $message['url'] }}" title="{{ $message['name'] }}">阅读全文<i class="fa fa-long-arrow-right"></i></a> </p>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
            @php
                $column = (new \App\CcForever\service\Repository())->column(3, ['name']);
                $messages = (new \App\CcForever\service\Repository())->messages(3, 1, 2, ['update_time' => 'DESC'], 1)
            @endphp
            @if(count($column))
                <div class="lastnews">
                    <div class="box_title clearfix"> <span><i class="icon fa fa-group"></i>{{ $column['name'] }}</span> <a class="fr" href="{{ $column['url'] }}" title="{{ $column['name'] }}">更多<i class="fa fa-angle-right"></i></a> </div>
                    @foreach($messages as $message)
                        <article class="lastnews_article clearfix">
                            <div class="lastnews_img fl">
                                <a href="{{ $message['url'] }}" title="{{ $message['name']  }}">
                                    <img src="{{ $message['image']  }}" alt="{{ $message['name']  }}">
                                </a>
                            </div>
                            <div class="lastnews_detail fr">
                                <header class="clearfix"><h3><a href="{{ $message['url'] }}" title="{{ $message['name'] }}">{{ $message['name']  }}</a></h3></header>
                                <p class="meta">
                                    <span><i class="fa fa-eye"></i>{{ $message['click'] }}</span>
                                    <span><i class="fa fa-clock-o"></i>{{ $message['update_time'] }}</span>
                                </p>
                                <p class="note m-multi-ellipsis"> {{ (new \App\CcForever\service\Util())->cutString($message['description'], 0, 12, true, '...')  }}</p>
                                <p class="read_all hidden-sm-md-lg"> <a href="{{ $message['url'] }}" title="{{ $message['name'] }}">阅读全文<i class="fa fa-long-arrow-right"></i></a> </p>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
            <div class="morenews clearfix">
                @php
                    $column = (new \App\CcForever\service\Repository())->column(4, ['name']);
                    $messages = (new \App\CcForever\service\Repository())->messages(4, 1, 6, ['update_time' => 'DESC'], 1)
                @endphp
                @if(count($column))
                    <div class="sportsnew fl">
                        <div class="box_title clearfix"><span><i class="icon more_icon fa fa-paper-plane-o"></i>{{ $column['name'] }}</span> <a class="fr"  href="{{ $column['url'] }}" title="{{ $column['name'] }}">更多<i class="fa fa-angle-right"></i></a> </div>
                        @foreach($messages as $message)
                            @if($loop->first)
                                <div class="big_block">
                                    <article class="morenews_article clearfix">
                                        <div class="morenews_img fl">
                                            <a href="{{ $message['url'] }}" title="{{ $message['name'] }}">
                                                <img src="{{ $message['image'] }}" alt="{{ $message['name'] }}">
                                            </a>
                                        </div>
                                        <div class="morenews_detail fr">
                                            <h3><a href="{{ $message['url'] }}" title="{{ $message['name'] }}">{{ $message['name'] }}</a></h3>
                                            <p class="note m-multi-ellipsis"> {{ (new \App\CcForever\service\Util())->cutString($message['description'], 0, 12, true, '...')  }}</p>
                                        </div>
                                    </article>
                                </div>
                            @endif
                        @endforeach
                        @foreach($messages as $message)
                            @if(!$loop->first)
                                <ul class="box">
                                    <li class="clearfix"> <i></i>
                                        <h4><a href="{{ $message['url'] }}" title="{{ $message['name'] }}">{{ $message['name'] }}</a></h4>
                                        <strong class="fr">[{{ $message['update_time'] }}]</strong> </li>
                                </ul>
                            @endif
                        @endforeach
                    </div>
                @endif
                @php
                    $column = (new \App\CcForever\service\Repository())->column(5, ['name']);
                    $messages = (new \App\CcForever\service\Repository())->messages(5, 1, 6, ['update_time' => 'DESC'], 1)
                @endphp
                @if(count($column))
                    <div class="sportsnew fl">
                        <div class="box_title clearfix"><span><i class="icon more_icon fa fa-paper-plane-o"></i>{{ $column['name'] }}</span><a class="fr" href="{{ $column['url'] }}" title="{{ $column['name'] }}">更多<i class="fa fa-angle-right"></i></a></div>
                        @foreach($messages as $message)
                            @if($loop->first)
                                <div class="big_block">
                                    <article class="morenews_article clearfix">
                                        <div class="morenews_img fl">
                                            <a href="{{ $message['url'] }}" title="{{ $message['name'] }}">
                                                <img src="{{ $message['image'] }}" alt="{{ $message['name'] }}">
                                            </a>
                                        </div>
                                        <div class="morenews_detail fr">
                                            <h3><a href="{{ $message['url'] }}" title="{{ $message['name'] }}">{{ $message['name'] }}</a></h3>
                                            <p class="note m-multi-ellipsis"> {{ (new \App\CcForever\service\Util())->cutString($message['description'], 0, 12, true, '...')  }}</p>
                                        </div>
                                    </article>
                                </div>
                            @endif
                        @endforeach
                        @foreach($messages as $message)
                            @if(!$loop->first)
                                <ul class="box">
                                    <li class="clearfix">
                                        <i></i>
                                        <h4><a href="{{ $message['url'] }}" title="{{ $message['name'] }}">{{ (new \App\CcForever\service\Util())->cutString($message['name'], 0, 19, true, '...')  }}</a></h4>
                                        <strong class="fr">[{{ $message['update_time'] }}]</strong>
                                    </li>
                                </ul>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        @include('style.right')
        <div class="friendlink fl">
            <div class="box_title"> <span><i class="icon link_icon fa fa-link"></i>友情链接</span> <span class="friendlink_ruler"></span> </div>
            <ul class="clearfix">
                @foreach($links as $link)
                    @if($link['follow'])
                        <li><a rel="follow" href='{{ $link['link'] }}' target='_blank'>{{ $link['name'] }}</a></li>
                    @else
                        <li><a href='{{ $link['link'] }}' target='_blank'>{{ $link['name'] }}</a></li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
</main>
@include('style.footer')
</body>
</html>
