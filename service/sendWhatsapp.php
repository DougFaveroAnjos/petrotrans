<?php

require __DIR__ . "/../vendor/autoload.php";

/**
 * SEND QUEUE
 */
$confiCron = (new \Source\Models\sendCronConfig())->find("type = 'whatsapp'")->fetch();
if($confiCron){
    //Verifica Dias
    $days = explode(";", $confiCron->days);
    $hday = date("D");
    if(in_array(days($hday), $days)){

        $start = date("H:i", strtotime($confiCron->start));
        $end = date("H:i", strtotime($confiCron->end));
        $hhr = date("H:m");

        //Horario
        if(($hhr >= $start) && ($hhr <= $end)){
            $whatsapp = (new \Source\Models\WhatsappQueue())->find("Cast(created_at as Date) = '".date("Y-m-d")."'")->count();
            if($whatsapp <= $confiCron->limite){
                $whatsappQueue = new \Source\Support\Whatsapp();
                $whatsappQueue->sendQueue();
            }
        }
    }
}else{
    $whatsappQueue = new \Source\Support\Whatsapp();
    $whatsappQueue->sendQueue();
}

function days(string $day){
    $array = [
        "Mon" => "seg",
        "Tue" => "ter",
        "Wed" => "qua",
        "Thu" => "qui",
        "Fri" => "sex",
        "Sat" => "sab",
        "Sun" => "dom",
    ];
    return $array[$day];
}
