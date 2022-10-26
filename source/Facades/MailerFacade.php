<?php


namespace Source\Facades;


use PHPMailer\PHPMailer\PHPMailer;

class MailerFacade
{

    public function send(array $data, string $email, array $path = null): bool
    {

        header('Content-Type: text/html; charset=UTF-8');

        $mail = new PHPMailer();

        $mail->isSMTP();
        $mail->Host = 'smtp.petrotrans.com.br';
        $mail->Port = 587;
        $mail->SMTPSecure = false;
        $mail->SMTPAutoTLS = false;
        $mail->SMTPAuth = true;
        $mail->Username = "comercial=petrotrans.com.br";
        $mail->Password = "171163@rp";
        $mail->setFrom("comercial@petrotrans.com.br", "COTACAO");
        $mail->addAddress($email, "Destinaratio");
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $data['assunto'];
        $mail->Body = $data['msg'];
        $mail->Body .= "<br><br><br>".$data['corpo'];
        $mail->Body .= "<hr>
                        <br>
                        <br>
                        <img src='".url("/theme/images/assinatura.png")."' alt='Assinatura'>";

//        if(isset($data['anexos']) && $data['anexos'] === "") {
//            foreach (explode(" ; ", $data['anexos']) as $anexo) {
//                $newAnexo = str_replace(" ", "%20", $anexo);
//
//                $mail->addStringAttachment(file_get_contents($newAnexo), pathinfo($newAnexo)['basename']);
//
//            }
//        }


        if(!empty($path) && is_array($path)){
            foreach ($path as $key) {
                $mail->addAttachment($key['arquivo'], $key['name']);
            }
        }

        if(!$mail->send()) {
            return false;
        }

        return true;
    }
}