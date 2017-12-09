
$().ready(function () {
    var currentPage = 1;
    $("#moreCars").click(function () {
        $.post(
            '/admin/cars',
            {
                page: ++currentPage
            },
            function (data) {
                $(data).hide().appendTo(".table-body").fadeIn(1000);
                if (currentPage === totalPages) {
                    $("#moreCars").remove();
                }
            }
        );
    });
});