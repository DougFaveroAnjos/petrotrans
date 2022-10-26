<?php


namespace Source\Models;


use CoffeeCode\DataLayer\DataLayer;

class TransportesModel extends DataLayer

{
    public function __construct()
    {
        parent::__construct("transportes", ["cotacao_id", "empresa_name", "status", "origem", "destino"]);
    }

    public function empresas()
    {
        return ((new EmpresasModel())->find("name = :empresa", "empresa={$this->empresa_name}")->fetch());
    }

    public function contato()
    {
        return (new ContatosModel())->find("empresa = :e", "e={$this->empresa_name}")->fetch();
    }

    public function cotacao()
    {
        return (new CotacoesModel())->find("id = :id", "id={$this->cotacao_id}")->fetch();
    }
}