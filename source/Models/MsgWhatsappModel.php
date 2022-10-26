<?php


namespace Source\Models;


use CoffeeCode\DataLayer\DataLayer;

class MsgWhatsappModel extends DataLayer
{

    public function __construct()
    {
        parent::__construct("msg_whatsapp", ['content']);
    }
}