$(document).ready(function () {
   $("#loginMessage").append('<p>Неверный логин или пароль</p>');
   $("#loginMessage").css('display', 'block');
   $("#loginMessage").addClass('alert-danger');
   $("#btn-login").click();
});