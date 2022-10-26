<?php


namespace Source\Models;


use CoffeeCode\DataLayer\DataLayer;

class ContatosEmpresaModel extends DataLayer
{

    public function __construct()
    {
        parent::__construct("contatosempresa", ["cnpj"], "id_contato_empresa", false);
    }

}
