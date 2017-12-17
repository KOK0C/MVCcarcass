
var currentPage = 1;
$("#moreCategory").click(function () {
    $.post(
        '/admin/categories',
        {
            page: ++currentPage
        },
        function (data) {
            $(data).hide().appendTo(".table-body").fadeIn(1000);
            if (currentPage === totalPages) {
                $("#moreCategory").remove();
            }
        }
    );
});