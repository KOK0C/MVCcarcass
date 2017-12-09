
$().ready(function () {
    var currentPage = 1;
    $("#moreUser").click(function () {
        $.post(
            '/admin/users',
            {
                page: ++currentPage
            },
            function (data) {
                $(data).hide().appendTo(".table-body").fadeIn(1000);
                if (currentPage === totalPages) {
                    $("#moreUser").remove();
                }
            }
        );
    });
});