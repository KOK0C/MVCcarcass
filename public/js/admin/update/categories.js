
$(".btn-update").click(function () {
    $.post(
        '/admin/categories/update',
        {
            id: $(this).data('id')
        },
        function (data) {
            $("#forModal").empty().append(data);
            $("#updateCategory").modal('show');
        }
    );
});