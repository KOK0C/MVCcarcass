
$().ready(function () {
    var currentPage = 1;
    var mark = $("#selectMarkCar");
    var uri;
    $("#moreCars").click(function () {
        if (mark.val() === 'all') {
            uri = '/admin/cars'
        } else {
            uri = '/admin/cars/mark/' + mark.val();
        }
        $.post(
            uri,
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
    mark.change(function () {
        if (mark.val() === 'all') {
            $("#carsForm").attr('action', '/admin/cars').submit();
        } else {
            $("#carsForm").attr('action', '/admin/cars/mark/' + mark.val()).submit();
        }
    });
});