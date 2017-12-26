
var slides = $("ol.list-unstyled li.sidebarItem");
var height = slides.length;
var slider = $("ol.list-unstyled");
var count = 0;

$("#upButton").click(function () {
    slider.animate({top: '+=120px'}, 500);
    $("#downButton").css('display', 'inline-block');
    count++;
    if (count === height - 4) {
        count = 0;
        $("#upButton").css('display', 'none');
    }
});
$("#downButton").click(function () {
    slider.animate({top: '-=120px'}, 500);
    $("#upButton").css('display', 'inline-block');
    count--;
    if (-count === height - 4) {
        count = 0;
        $("#downButton").css('display', 'none');
    }
});
$("#moreBrands").click(function () {
    $.post(
        '/more/brands',
        {},
        function (data) {
            $("#forModal").empty().append(data);
            $("#allBrand").modal('show');
        }
    );
});