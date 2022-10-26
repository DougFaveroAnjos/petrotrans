<?php
    $to = 'comercial@petrotrans.com.br';
    $firstname = $_POST["fname"];
    $email= $_POST["email"];
    $wpp= $_POST["wpp"];
    $text= $_POST["message"];
    


    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= "From: " . $email . "\r\n"; // Sender's E-mail
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

    $message ='<table style="width:100%">
        <tr>
            <td>'.$firstname.'</td>
        </tr>
        <tr><td>Email: '.$email.'</td></tr>
        <tr><td>Celular: '.$wpp.'</td></tr>
        <tr><td>Mensagem: '.$text.'</td></tr>
        
    </table>';

    if (@mail($to, $email, $message, $headers))
    {
        echo 'A mensagem foi enviada!';
    }else{
        echo 'Infelizmente a mensagem não foi enviada. Tente novamente.';
    }

?>
