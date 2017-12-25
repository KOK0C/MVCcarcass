$(".table-body").on("click", ".btn-delete", function () {
    var elem = $(this).parent().parent();
    $.post(
        deleteUri,
        {
            id: $(this).data('id')
        },
        function (data) {
            if (data === 'true') {
                elem.fadeOut(300, function () {
                    elem.remove();
                });
            }
        }
    );
});