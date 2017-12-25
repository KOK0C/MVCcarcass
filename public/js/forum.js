
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

var comments = $(".comments");
comments.on("click", ".btn-delete", function () {
    var elem = $(this).parent().parent();
    $.post(
        '/forum/ajax/deleteComment',
        {
            id: $(this).data('id')
        },
        function (data) {
            if (data === 'true') {
                elem.fadeOut(300, function () {
                    elem.remove();
                });
            }
        }
    );
});
comments.on("click", ".btn-update", function () {
    $.post(
        '/forum/ajax/showUpdateComment',
        {
            id: $(this).data('id')
        },
        function (data) {
            $("#forModal").empty().append(data);
            $("#updateComments").modal('show');
        }
    );
});
$("#forModal").on('click', '.btnUpdateComment', function () {
    $.post(
        '/forum/ajax/updateComment',
        {
            idComment: $("#updateCommentId").val(),
            text: $("#textComment").val()
        },
        function (data) {
            data = $.parseJSON(data);
            $('[data-idComment=' + $("#updateCommentId").val() + ']').children(".commentText").empty().append(data.text);
            $("#updateComments").modal('hide');
        }
    );
});