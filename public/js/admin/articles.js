
$().ready(function () {
    var currentPage = 1;
    $("#moreArticle").click(function () {
        $.post(
            '/admin/articles',
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
});