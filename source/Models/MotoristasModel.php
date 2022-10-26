<?php


namespace Source\Models;


use CoffeeCode\DataLayer\DataLayer;

class MotoristasModel extends DataLayer
{

    public function __construct()
    {
        parent::__construct("motoristas", ['name', 'cpf', 'placa_veiculo']);
    }



}