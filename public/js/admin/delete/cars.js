
$(".table-body").on("click", ".btn-delete", function () {
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