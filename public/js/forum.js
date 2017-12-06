
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
                $(data).hide().appendTo(".forum-themes").fadeIn(1000);
                if (currentPage === totalPage) {
                    $("#moreTheme").remove();
                }
            }
        );
    });
    var currentCommentPage = 1;
    $("#moreComments").click(function () {
        $.post(
            '/forum/ajax/loadComment',
            {
                page: ++currentCommentPage,
                theme: themeId
            },
            function (data) {
                $(data).hide().appendTo(".comments").fadeIn(1000);
                if (currentCommentPage === totalComments) {
                    $("#moreComments").remove();
                }
            }
        );
    });
    $("#addComment").click(function () {
        var comment = $("#commentId");
        if (comment.val().length > 0) {
            $.post(
                '/forum/ajax/createComment',
                {
                    theme_id: themeId,
                    text: comment.val()
                },
                function (data) {
                    comment.css('border-color', 'rgb(169, 169, 169)').prop('placeholder', 'Сообщение').val('');
                    $(data).hide().appendTo(".comments").fadeIn(1000);
                }
            );
        } else {
            comment.css('border-color', '#d9534f').prop('placeholder', 'Напишите коментарий');
        }
    });
});