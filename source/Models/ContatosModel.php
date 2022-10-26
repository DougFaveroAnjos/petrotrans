<?php


namespace Source\Models;


use CoffeeCode\DataLayer\DataLayer;

class ContatosModel extends DataLayer
{

    public function __construct()
    {
        parent::__construct("contatos", ["user_name"], "id", false);
  //    parent::__construct("contatos_empresa", ["user_name"], "id", false);

    }


}