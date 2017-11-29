$( "#markCar" ).change(function() {
    var mark = $("#markCar");
    if (mark.val() === 'all') {
        $("#form-reviews").attr('action', '/reviews').submit();
    } else {
        $("#form-reviews").attr('action', '/reviews/mark/' + mark.val()).submit();
    }
});

$("#modelCar").change(function () {
    var mark = $("#markCar");
    var model = $("#modelCar");
    if (mark.val() === 'all' && model.val() === 'all') {
        $("#form-reviews").attr('action', '/reviews').submit();
    } else if (model.val() === 'all') {
        $("#form-reviews").attr('action', '/reviews/mark/' + mark.val()).submit();
    } else {
        $("#form-reviews").attr('action', '/reviews/mark/' + mark.val() + '/model/' + model.val()).submit();
    }
});

$("#selectMark").change(function () {
    var mark = $("#selectMark");
    $.post(
        '/reviews',
        {mark: mark.val()},
        function (data) {
            data = $.parseJSON(data);
            $("#selectModel").prop("disabled", false).empty().append('<option></option>');
            $.each(data, function () {
                $("#selectModel").append("<option value='" + this.model + "'>" + this.model + "</option>");
            });
        }
    )
});