
$(".btn-update").click(function () {
    $.post(
        '/admin/users/update',
        {
            id: $(this).data('id')
        },
        function (data) {
            console.log(data);
            $("#forModal").empty().append(data);
            $("#updateUser").modal('show');
        }
    );
});