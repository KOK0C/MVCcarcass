if (loginSuccess) {
    $(document).ready(function () {
        $("#loginMessage").append('<p>Регистрация успешно завершена можете войти</p>');
        $("#loginMessage").css('display', 'block');
        $("#loginMessage").addClass('alert-success');
        $("#btn-login").click();
    });
} else {
    $(document).ready(function () {
        $("#loginMessage").append('<p>Неверный логин или пароль</p>');
        $("#loginMessage").css('display', 'block');
        $("#loginMessage").addClass('alert-danger');
        $("#btn-login").click();
    });
}