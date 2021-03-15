<div class="footer">
    <div class="container">
        <div class="footer_wrap">
            <div class="follow_us fl">
                <div class="footer_title">关注我们</div>
                <img src="{{ $configs['config_bottom_logo'] }}"> </div>
            <div class="footer_contact fl">
                <div class="footer_title"> 联系我们 </div>
                <ul>
                    <li>合作或者问题咨询可联系：</li>
                    <li>QQ：{{ $configs['config_service_qq'] }}</li>
                    <li>微信：{{ $configs['config_service_wechat'] }}</li>
                    <li>邮箱：{{ $configs['config_service_email'] }}</li>
                </ul>
            </div>
            <div class="footer_about fr">
                <div class="footer_title">关于我们</div>
                <p>{{ $configs['config_seo_description'] }}</p>
                <p class="copyright"> <span>@php echo $configs['config_copyright'].$configs['config_record_number']@endphp</span> </p>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>
<div id="mask"></div>
<div id="backtop"><span id="gotop1" style=""><img src="/style/images/huojian.svg" alt="返回顶部小火箭"></span></div>
<script src="/style/js/custom.js"></script>
<!-- Initialize Swiper -->
<script>
    var swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
        paginationClickable: true,
        loop: true,
        autoplay:2000,
        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
        spaceBetween: 30,
        effect: 'fade',
    });
</script>
<script>
    function searchToggle(obj, evt){
        var container = $(obj).closest('.search-wrapper');

        if(!container.hasClass('active')){
            container.addClass('active');
            evt.preventDefault();
        }
        else if(container.hasClass('active') && $(obj).closest('.input-holder').length == 0){
            container.removeClass('active');
            // clear input
            container.find('.search-input').val('');
            // clear and hide result container when we press close
            container.find('.result-container').fadeOut(100, function(){$(this).empty();});
        }
    }
</script>
<script>
    $(function() {
        $("#gotop1,#gotop2").click(function(e) {
            TweenMax.to(window, 1.5, {scrollTo:0, ease: Expo.easeInOut});
            var huojian = new TimelineLite();
            huojian.to("#gotop1", 1, {rotationY:720, scale:0.6, y:"+=40", ease:  Power4.easeOut})
                .to("#gotop1", 1, {y:-1000, opacity:0, ease:  Power4.easeOut}, 0.6)
                .to("#gotop1", 1, {y:0, rotationY:0, opacity:1, scale:1, ease: Expo.easeOut, clearProps: "all"}, "1.4");
        });
    });
</script>
