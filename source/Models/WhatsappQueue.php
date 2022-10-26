<?php


namespace Source\Models;


use CoffeeCode\DataLayer\DataLayer;

class WhatsappQueue extends DataLayer
{
    public function __construct()
    {
        parent::__construct("whatsapp_queue", ['message', 'recipient_number']);
    }
}