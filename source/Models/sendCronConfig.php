<?php


namespace Source\Models;


use CoffeeCode\DataLayer\DataLayer;

class sendCronConfig extends DataLayer
{
    public function __construct()
    {
        parent::__construct("config_cron", ['days', 'start', 'end', "type"]);
    }
}