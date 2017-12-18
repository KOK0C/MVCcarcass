
$(".btn-update").click(function () {
    $.post(
        '/admin/car/update',
        {
            id: $(this).data('id')
        },
        function (data) {
            $("#forModal").empty().append(data);
            $("#modalCars").modal('show');
        }
    );
});