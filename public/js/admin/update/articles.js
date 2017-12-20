
$("#imageArticle").change(function () {
    $("#updateArticle").attr('enctype', 'multipart/form-data');
    $(this).attr('name', 'image');
});