String.prototype.ucWords = function() {
    return this.replace(/^(.)|\s(.)/g, function ( $1 ) { return $1.toUpperCase ( ); } );
};
$().ready(function () {
    var currentPage = 1;
    var category = $("#selectCategory");
    var uri;
    $("#moreArticle").click(function () {
        if (category.val() === 'all') {
            uri = '/admin/articles'
        } else {
            uri = '/admin/articles/' + category.val();
        }
        $.post(
            uri,
            {
                page: ++currentPage
            },
            function (data) {
                $(data).hide().appendTo(".table-body").fadeIn(1000);
                if (currentPage === totalPages) {
                    $("#moreArticle").remove();
                }
            }
        );
    });
    category.change(function () {
        if (category.val() === 'all') {
            $("#categoryForm").attr('action', '/admin/articles').submit();
        } else {
            $("#categoryForm").attr('action', '/admin/articles/' + category.val()).submit();
        }
    });
    $("#selectMark").change(function () {
        var markCar = $("#selectMark");
        var modelCar = $("#selectModel");
        if (markCar.val() === '') {
            modelCar.empty().append('<option>Модель</option>').prop("disabled", true);
        } else {
            $.post(
                '/reviews',
                {mark: markCar.val()},
                function (data) {
                    modelCar.prop("disabled", false).empty().append('<option></option>');
                    $.each($.parseJSON(data), function () {
                        modelCar.append("<option value='" + this.model.split(' ').join('-').toLowerCase() + "'>"
                            + this.model.toLowerCase().ucWords() + "</option>");
                    });
                }
            )
        }
    });
    $("#imageArticle").change(function () {
        var preview = document.querySelector('img');
        var file = document.querySelector('input[type=file]').files[0];
        var reader = new FileReader();
        reader.onload = function () {
            preview.src = reader.result;
        };
        if (file) {
            reader.readAsDataURL(file);
        }
    })
});