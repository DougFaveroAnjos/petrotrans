<?php


namespace Source\Models;


use CoffeeCode\DataLayer\DataLayer;

class EmpresasModel extends DataLayer
{

    public function __construct()
    {
        parent::__construct("empresas", ["dono_id", "name", "estado", "cidade", "cnpj"]);
    }

}