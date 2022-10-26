<?php

require __DIR__ . "/../vendor/autoload.php";

$cotacoes = (new \Source\Models\CotacoesModel())->find("status = 'Aguardando Cliente'")->fetch(true);

if($cotacoes){
    foreach ($cotacoes as $cotacao) {
        //Envio de email com data pré-definida
        if(!empty($cotacao->cobranca_data) && (strtotime($cotacao->cobranca_data) == strtotime(date("Y-m-d")))){
            //Verifica se ja foi enviado um e-mail
            if(!$cotacao->send_at){
                foreach ($cotacao->contatos() as $email => $name) {
                    sendMail($cotacao->id, $email, $name);
                }
            }
        }

        //Recorrência de email
        if(!empty($cotacao->cobranca)){
            if(!$cotacao->send_at){
                foreach ($cotacao->contatos() as $email => $name) {
                    sendMail($cotacao->id, $email, $name);
                }
            }else{
                if(strtotime(date("Y-m-d")) == strtotime(date('Y-m-d', strtotime($cotacao->send_at. " + {$cotacao->cobranca} days")))){
                    foreach ($cotacao->contatos() as $email => $name) {
                        sendMail($cotacao->id, $email, $name);
                    }
                }
            }
        }
    }
}

function sendMail(int $id, string $email, string $name, string $subject = "EXEMPLO COBRANÇA CLIENTE")
{
    (new \Source\Support\Email())->bootstrap(
        $subject,
        $email,
        $name,
        null,
        "cotacao"
    )->view("email",
        [
            "subject" => $subject,
            "cte" => null,
            "responsavel" => $name,
            "status" => null,
            "empresa" => null,
            "valor" => "R$ ".str_price(0),
            "origem" => null,
            "destino" => null
        ])->queue();

    $update = (new \Source\Models\CotacoesModel())->findById($id);
    $update->send_at = date("Y-m-d");
    $update->save();
}