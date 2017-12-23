
$().ready(function () {
    var currentPage = 1;
    var mark = $("#markCarSelect");
    var model = $("#modelCarSelect");
    var url = '';
    mark.change(function() {
        if (mark.val() === 'all') {
            $("#selectCarForm").attr('action', '/admin/reviews').submit();
        } else {
            $("#selectCarForm").attr('action', '/admin/reviews/mark/' + mark.val()).submit();
        }
    });

    model.change(function () {
        if (mark.val() === 'all' && model.val() === 'all') {
            $("#selectCarForm").attr('action', '/admin/reviews').submit();
        } else if (model.val() === 'all') {
            $("#selectCarForm").attr('action', '/admin/reviews/mark/' + mark.val()).submit();
        } else {
            $("#selectCarForm").attr('action', '/admin/reviews/mark/' + mark.val() + '/model/' + model.val()).submit();
        }
    });

    $("#moreReviews").click(function () {
        if (mark.val() === 'all') {
            url = '/admin/reviews';
        } else if (mark.val() !== 'all' && model.val() === 'all') {
            url = '/admin/reviews/mark/' + mark.val();
        } else if (mark.val() !== 'all' && model.val() !== 'all') {
            url = '/admin/reviews/mark/' + mark.val() + '/model/' + model.val();
        }
        $.post(
            url,
            {page: ++currentPage},
            function (data) {
                $(data).hide().appendTo(".table-body").fadeIn(1000);
                console.log(data);
                if (currentPage === totalPages) {
                    $("#moreReviews").remove();
                }
            }
        );
    });
    $(".showMore").click(function () {
        $.post(
            '/admin/reviews/show',
            {
                id: $(this).data('id')
            },
            function (data) {
                $("#forModal").empty().append(data);
                $("#reviewModal").modal('show');
            }
        );
    });
    $(".showUser").click(function () {
        $.post(
            '/admin/users/show',
            {
                id: $(this).data('id')
            },
            function (data) {
                $("#forModal").empty().append(data);
                $("#reviewModal").modal('show');
            }
        );
    });
});