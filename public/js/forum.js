
$().ready(function () {
    var currentPage = 1;
    $("#moreTheme").click(function () {
        $.post(
            '/forum/ajax/loadTheme',
            {
                page: ++currentPage,
                parent_id: parentId
            },
            function (data) {
                $(".forum-themes").append(data);
                if (currentPage === totalPage) {
                    $("#moreTheme").remove();
                }
            }
        );
    });
});