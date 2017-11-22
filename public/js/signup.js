if (window.email !== undefined) {
    for (var i = 0; i < email.length; i++) {
        $("#errorEmailList").append('<li>' + email[i] + '</li>');
    }
    $("#errorEmail").css("display", "block");
}

if (window.pass !== undefined) {
    for (var j = 0; j < pass.length; j++) {
        $("#errorPasswordList").append('<li>' + pass[j] + '</li>');
    }
    $("#errorPassword").css("display", "block");
}

if (window.pass_again !== undefined) {
    for (var k = 0; k < pass_again.length; k++) {
        $("#errorPasswordAgainList").append('<li>' + pass_again[k] + '</li>');
    }
    $("#errorPasswordAgain").css("display", "block");
}

$("#btn-signup").click();