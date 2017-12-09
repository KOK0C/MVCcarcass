
$().ready(function () {
    var currentPage = 1;
    var category = $("#selectCategory");
    var uri;
    $("#moreArticle").click(function () {
        if (category.val() === 'all') {
            uri = '/admin/articles'
        } else {
            uri = '/admin/articles/' + category.val();
        }
        $.post(
            uri,
            {
                page: ++currentPage
            },
            function (data) {
                $(data).hide().appendTo(".table-body").fadeIn(1000);
                if (currentPage === totalPages) {
                    $("#moreArticle").remove();
                }
            }
        );
    });
    category.change(function () {
        if (category.val() === 'all') {
            $("#categoryForm").attr('action', '/admin/articles').submit();
        } else {
            $("#categoryForm").attr('action', '/admin/articles/' + category.val()).submit();
        }
    });
});