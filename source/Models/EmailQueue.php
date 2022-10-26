<?php


namespace Source\Models;


use CoffeeCode\DataLayer\DataLayer;

class EmailQueue extends DataLayer
{
    public function __construct()
    {
        parent::__construct("mail_queue", ['subject', 'body', 'from_email', 'from_name', 'recipient_email', 'recipient_name']);
    }
}