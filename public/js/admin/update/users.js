
$(".table-body").on("click", ".btn-update", function () {
    $.post(
        '/admin/users/update',
        {
            id: $(this).data('id')
        },
        function (data) {
            $("#forModal").empty().append(data);
            $("#updateUser").modal('show');
        }
    );
});