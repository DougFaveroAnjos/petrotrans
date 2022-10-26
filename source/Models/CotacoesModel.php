<?php


namespace Source\Models;


use CoffeeCode\DataLayer\DataLayer;

class CotacoesModel extends DataLayer
{
public function __construct()
{
    parent::__construct("cotacoes", [
        "vendedor_id", "cliente_id",
        "valor_cotacao", "peso", "tipo_carga",
        "prazo_maximo", "km"
    ]);
}

    /**
     * @return bool
     */

public function save(): bool
{
    if(
        //!$this->validateVendedor() ||
        !parent::save()
    ) {
       return false;
    }

    return true;
}

public function contatos(): ?array
{
    $cnpj = (new EmpresasModel())->find("name = :name", "name={$this->cliente}")->fetch()->cnpj;
    $contatos = (new ContatosModel())->find("cnpj = {$cnpj}")->fetch(true);
    $emails = array();
    foreach ($contatos as $contato) {
        $emails[ $contato->email] = $contato->responsavel;
    }
    return $emails;
}
    /**
     * @return bool
     */

    /*
public function validateVendedor(): bool
{

    if(
        empty($this->vendedor_id) ||
        !(new Users())->findById($this->vendedor_id)->fetch()
    ) {
        echo $this->fail = new Exception("Informe um vendedor que seja um usuário");
        return false;
    }

    return true;
}
    */
}
