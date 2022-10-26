<?php


namespace Source\Models;


use CoffeeCode\DataLayer\DataLayer;

class ColetasModel extends DataLayer
{

    public function __construct()
    {
        parent::__construct("coletas", ["transporte", "motorista", "date"]);
    }

    public function getMotorista(): ?MotoristasModel
    {
        return (new MotoristasModel())->find("name = '{$this->motorista}'")->fetch();
    }

    public function getCotacoes(): ?CotacoesModel
    {
        return (new CotacoesModel())->findById($this->coleta_id);
    }

}