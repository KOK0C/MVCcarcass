
$(".btn-delete").click(function () {
    $.post(
        '/admin/car/delete',
        {
            id: $(this).data('id')
        },
        function (data) {
            $("#forModal").empty().append(data);
            $("#modalCars").modal('show');
        }
    );
});