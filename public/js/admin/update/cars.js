
$(".btn-update").click(function () {
    $.post(
        '/admin/car/update',
        {
            id: $(this).data('id')
        },
        function (data) {
            $("#forModal").empty().append(data);
            $("#modalCars").modal('show');
        }
    );
});

$("#imageIconCar").change(function () {
    $("#updateCar").attr('enctype', 'multipart/form-data');
    $(this).attr('name', 'icon');
});

$("#imageHeadCar").change(function () {
    $("#updateCar").attr('enctype', 'multipart/form-data');
    $(this).attr('name', 'img');
});

$("#imageLogo").change(function () {
    $("#updateMark").attr('enctype', 'multipart/form-data');
    $(this).attr('name', 'logo');
});