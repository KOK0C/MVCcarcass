<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 24.12.2017
 * Time: 11:19
 */

namespace IhorRadchenko\App\Components;

class SendMail
{
    private $mailer;
    private $message;

    public function __construct()
    {
        $transport = (new \Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))
            ->setUsername(Config::getInstance()->smtp['name'])
            ->setPassword(Config::getInstance()->smtp['pass']);
        $this->mailer = new \Swift_Mailer($transport);
    }

    public function confirmReg(string $email, string $token)
    {
        $this->message = (new \Swift_Message('Поздравляем с регистрацией'))
            ->setFrom(['igorradchenko1995@gmail.com' => 'Администрация'])
            ->setTo([$email => 'Пользователь'])
            ->setBody('Поздравляем с регистрацией, для подтверждения перейдите по <a href="http://mvccarcass/confirm/' . $token . '">адресу</a>', 'text/html');
    }

    public function send()
    {
        $this->mailer->send($this->message);
    }
}