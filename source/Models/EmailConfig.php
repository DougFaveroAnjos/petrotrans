<?php


namespace Source\Models;


use CoffeeCode\DataLayer\DataLayer;

class EmailConfig extends DataLayer
{
    public function __construct()
    {
        parent::__construct("config_email", ['host', 'port', 'user', 'pass', 'name', 'address']);
    }
}