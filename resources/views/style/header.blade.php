<header class="header">
    <div class="container">
        <div class="logo">
            <h1>
                <a href="/" title="{{ $configs['config_name'] }}">
                    <img src="{{ $configs['config_logo'] }}" alt="{{ $configs['config_name'] }}" title="{{ $configs['config_name'] }}">
                </a>
            </h1>
        </div>
        <div id="m-btn" class="m-btn"><i class="fa fa-bars"></i></div>
        <form  class="s-form container" action="/search.html">
            <div class="search-wrapper">
                <div class="input-holder">
                    <input type="text" name="search" class="search-input" placeholder="请输入搜索词" />
                    <button type="submit" id="submit-bt" class="search-icon" onclick="searchToggle(this, event);"> <span></span> </button>
                </div>
                <span class="close" onclick="searchToggle(this, event);"></span>
            </div>
        </form>
        <nav class="nav-bar" id="nav-box" data-type="index"  data-infoid="index">
            <ul class="nav">
                @foreach($navigations as $navigation)
                    @if($navigationId === $navigation['id'])
                        <li class="active">
                            <a href="{{ $navigation['url'] }}">{{ $navigation['name'] }}</a>
                            @if(count($navigation['children']))
                                <ul class="sub-nav">
                                    @foreach($navigation['children'] as $child)
                                        <li><a href="{{ $child['url'] }}">{{ $child['name'] }}</a></li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @else
                        <li>
                            <a href="{{ $navigation['url'] }}">{{ $navigation['name'] }}</a>
                            @if(count($navigation['children']))
                                <ul class="sub-nav">
                                    @foreach($navigation['children'] as $child)
                                        <li><a href="{{ $child['url'] }}">{{ $child['name'] }}</a></li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endif
                @endforeach
            </ul>
        </nav>
    </div>
</header>
