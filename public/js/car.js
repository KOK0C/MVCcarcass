
$().ready(function () {
    var currentCarNews = 1;
    $("#moreNews").click(function () {
        $.post(
            '/mark/ajax/loadNews',
            {
                page: ++currentCarNews,
                model: modelCar
            },
            function (data) {
                $(data).hide().appendTo(".post-mainPage").fadeIn(1000);
                if (currentCarNews === totalPage) {
                    $("#moreNews").remove();
                }
            }
        );
    });
});