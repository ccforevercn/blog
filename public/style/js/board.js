var url = location.origin + "/api/v1/cms"
function count() {
    $.ajax({
        url: url + "/board/count",
        type: "GET",
        success: function(result){
            $('#board-count').html(result.data.count)
            list(result.data.count);
        }
    });
}
count();
function list(limit) {
    $.ajax({
        url: url + "/board/list",
        data: {'page': 1, 'limit' : limit},
        type: "GET",
        success: function(result){
            var list = result.data;
            var html = '';
            for (var index in list) {
                html += "<div class='cont'>";
                html += "<div class='text'>";
                html += "<p class='tit'><span class='name'>"+ list[index].speak + "</span><span class='data'>" + list[index].add_time + "</span></p>";
                html += "<p class='ct'>" + list[index].content + "</p>";
                html += "</div>";
                html += "</div>";
            }
            $('#board-list').empty()
            $('#board-list').append(html)
        }
    });
}

$('#board-form').submit(function (event) {
    event.preventDefault();
    var content = $('#board-content').val();
    if(content !== undefined && content.length > 0) {
        $.ajax({
            url: url + "/board/stay",
            type: "POST",
            data: {'content': content},
            success: function(result){
                alert(result.message)
                count()
            }
        });
    }else {
        alert("请填写留言内容")
        return;
    }
});
