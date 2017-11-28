$( "#markCar" ).change(function() {
    var mark = $("#markCar");
    console.log(mark.val());
    if (mark.val() === 'all') {
        $("#form-reviews").attr('action', '/reviews').submit();
    } else {
        $("#form-reviews").attr('action', '/reviews/mark/' + $("#markCar").val()).submit();
    }
});