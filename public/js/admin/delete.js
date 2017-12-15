$(".btn-delete").click(function () {
    var elem = $(this).parent().parent();
    $.post(
        '/delete/article',
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