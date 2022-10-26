<?php


namespace Source\Models;


use CoffeeCode\DataLayer\DataLayer;

class Recovers extends DataLayer
{
public function __construct()
{
    parent::__construct("recover", ["token", "email"], "id", false);
}
}