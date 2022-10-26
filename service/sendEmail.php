<?php

require __DIR__ . "/../vendor/autoload.php";

/**
 * SEND QUEUE
 */
$confiCron = (new \Source\Models\sendCronConfig())->find("type = 'email'")->fetch();
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
            $mail = (new \Source\Models\EmailQueue())->find("Cast(created_at as Date) = '".date("Y-m-d")."'")->count();
            if($mail <= $confiCron->limite){
                $emailQueue = new \Source\Support\Email();
                $emailQueue->sendQueue();
            }
        }
    }
}else{
    $emailQueue = new \Source\Support\Email();
    $emailQueue->sendQueue();
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
