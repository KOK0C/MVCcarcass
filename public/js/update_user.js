if (window.email !== undefined) {
    for (var i = 0; i < email.length; i++) {
        $("#errorEmailUpUserList").append('<li>' + email[i] + '</li>');
    }
    $("#errorEmailUpUser").css("display", "block");
}

if (window.f_name !== undefined) {
    for (var j = 0; j < f_name.length; j++) {
        $("#errorFNameList").append('<li>' + f_name[j] + '</li>');
    }
    $("#errorFName").css("display", "block");
}

if (window.l_name !== undefined) {
    for (var k = 0; k < l_name.length; k++) {
        $("#errorLNameList").append('<li>' + l_name[k] + '</li>');
    }
    $("#errorLName").css("display", "block");
}

if (window.phone_number !== undefined) {
    for (var n = 0; n < phone_number.length; n++) {
        $("#errorPhoneNumberList").append('<li>' + phone_number[n] + '</li>');
    }
    $("#errorPhoneNumber").css("display", "block");
}