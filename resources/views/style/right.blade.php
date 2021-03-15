<aside class="aside fr hidden-sm-md-lg">
    <div id="about_intro" class="widget widget_about_intro">
        <div class="widget-content about_intro">
            <div class="side_about" style="background-image: url(/style/images/zb.jpg);">
                <div class="side_mask"> <span><img src="{{ $configs['config_bottom_logo'] }}"></span>
                    <h3><a>关于本站</a></h3>
                    <p>{{ $configs['config_seo_description'] }}</p>
                    @php
                        $statistics = (new \App\CcForever\service\Repository())->statistics()
                    @endphp
                    <ul class="side_count clearfix">
                        <li class="fl"><span><i class="fa fa-file-text-o"></i>文章</span><b>{{ $statistics['message'] }}</b></li>
                        <li class="fl"><span><i class="fa fa-comment-o"></i>查看</span><b>{{ $statistics['click'] }}</b></li>
                        <li class="fl"><span><i class="fa fa-tag"></i>标签</span><b>{{ $statistics['tag'] }}</b></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div id="aside_hot" class="widget widget_aside_hot">
        <div class="box_title side_title clearfix"> <span><i class="icon side_icon fa fa-file-text"></i>阅读排行</span></div>
        @php
            $clickMessages = (new \App\CcForever\service\Repository())->messages(0, 1, 10, ['click' => 'DESC'], 0)
        @endphp
        @if(count($clickMessages))
            <ul class="widget-content aside_hot">
                @foreach($clickMessages as $message)
                    <li class="clearfix">
                        <i class="fl side_hot_num side_hot_num-1">{{ $loop->iteration }}</i>
                        <a href="{{ $message['url'] }}" title="{{ $message['name'] }}">{{ $message['name'] }}</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
    <div id="aside_hot_comment" class="widget widget_aside_hot_comment">
        @php
            $messagesHot = (new \App\CcForever\service\Repository())->messages(0, 1, 8, ['update_time' => 'DESC'], 2)
        @endphp
        @if(count($messagesHot))
            <div class="box_title side_title clearfix"><span><i class="icon side_icon fa fa-file-text"></i>热门推荐</span></div>
            <ul class="widget-content aside_hot_comment">
                @foreach($messagesHot as $message)
                    <li>
                        <a class="clearfix" href="{{ $message['url'] }}" title="{{ $message['name'] }}">
                            <span class="side_comment_img fl"><img src="{{ $message['image'] }}" alt="{{ $message['name'] }}"></span>
                            <div class="side_comment_text fl">
                                <p>{{ $message['name'] }}</p>
                                <p class="meta"><i class="fa fa-clock-o">&nbsp;{{ $message['update_time'] }}</i><i class="fa fa-eye">&nbsp;{{ $message['click'] }}</i></p>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
    @php
       $tags = (new \App\CcForever\service\Repository())->tags();
    @endphp
    <div id="divTags" class="widget widget_tags">
        <div class="box_title side_title clearfix"><span><i class="icon side_icon fa fa-file-text"></i>标签列表</span></div>
        <ul class="widget-content divTags">
            <li>
            @foreach($tags as $tag)
                    <a href='/tags.php?{{ $tag['name'] }}'>{{ $tag['name'] }}</a>
            @endforeach
            </li>
        </ul>
    </div>
</aside>
