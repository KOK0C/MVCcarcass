if (window.old_pass !== undefined) {
    for (var i = 0; i < old_pass.length; i++) {
        $("#errorOldPasswordList").append('<li>' + old_pass[i] + '</li>');
    }
    $("#errorOldPassword").css("display", "block");
}

if (window.pass !== undefined) {
    for (var j = 0; j < pass.length; j++) {
        $("#errorPassword-ChangePasswordList").append('<li>' + pass[j] + '</li>');
    }
    $("#errorPassword-ChangePassword").css("display", "block");
}

if (window.pass_again !== undefined) {
    for (var k = 0; k < pass_again.length; k++) {
        $("#errorPasswordAgain-ChangePasswordList").append('<li>' + pass_again[k] + '</li>');
    }
    $("#errorPasswordAgain-ChangePassword").css("display", "block");
}