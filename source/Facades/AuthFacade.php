<?php


namespace Source\Facades;


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use Source\Models\Recovers;
use Source\Models\Users;

class AuthFacade
{
public function __construct()
{
    if (!session_id()) {
        session_start();
        $_SESSION['logged'] = false;
    }
}

public function auth(Users $user ,array $data): bool
{

    $login = $user->find("email = :email AND status != 'waiting'", "email={$data['email']}")->fetch();
    if($login && password_verify($data["password"], $login->password)) {
        $_SESSION['logged'] = true;
        $_SESSION['id'] = $login->id;
        $_SESSION['name'] = $user->find("email = :email", "email={$data['email']}")->fetch()->first_name;
        return true;
    }

    $_SESSION['logged'] = false;
    return false;
}

public function recover(string $token, array $data): bool
{

    $link = url("new-password?token=".$token);
    $try = new Recovers();
    $try->token = $token;
    $try->email = $data['email'];
    $try->expires = time() + 1800;

    if(!$try->save()) {
        return false;
    }

    $mail = new PHPMailer();

    $mail->isSMTP();
    $mail->Host = 'smtp.petrotrans.com.br';
    $mail->Port = 587;
    $mail->SMTPSecure = false;
    $mail->SMTPAutoTLS = false;
    $mail->SMTPAuth = true;
    $mail->Username = "comercial=petrotrans.com.br";
    $mail->Password = "171163@rp";
    $mail->setFrom("comercial@petrotrans.com.br", "Recuperar Senha");
    $mail->addReplyTo($data['email'], "Recuperar Senha");
    $mail->addAddress($data['email'], "Recuperar Senha");
    $mail->isHTML(false);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = 'Recuperar Senha';

    $mail->Body = "Link para recuperação de senha: ".$link;

    if(!$mail->send()) {
        return false;
    }

    return true;
}
}