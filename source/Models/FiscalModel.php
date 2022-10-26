<?php


namespace Source\Models;


use CoffeeCode\DataLayer\DataLayer;

class FiscalModel extends DataLayer
{
public function __construct()
{
    parent::__construct("fiscal", ['transporte', 'motorista', 'cotacao', 'coleta', 'empresa']);
}

public function findTransportes(?string $terms = null, ?string $params = null, string $columns = null)
{
    if($columns){
        $columns = ", {$columns}";
    }

    if ($terms) {
        $this->statement = "SELECT 
f.id id, f.empresa empresa, f.cotacao cotacao, f.motorista motorista, f.transporte transporte, t.liberacao liberacao,
f.cte cte, f.mdfe mdfe, t.status status, f.anexos_cte anexos_cte, t.destino destino, t.origem origem, f.pdf_cte pdf_cte, f.pdf_mdfe pdf_mdfe, boletos boletos {$columns} FROM fiscal as f JOIN transportes AS t ON f.transporte = t.id WHERE {$terms}";
        parse_str($params, $this->params);
        return $this;
    }

    $this->statement = "SELECT  
f.id id, f.empresa empresa, f.cotacao cotacao, f.motorista motorista, f.transporte transporte, t.liberacao liberacao,
 f.cte cte, f.mdfe mdfe, t.status status, f.anexos_cte anexos_cte, t.destino destino, t.origem origem, f.pdf_cte pdf_cte, f.pdf_mdfe pdf_mdfe, boletos boletos {$columns}
 FROM fiscal as f JOIN transportes AS t ON f.transporte = t.id
";
    return $this;
}
public function transportes()
{
    return ((new TransportesModel())->find("id = :id", "id={$this->transporte}")->fetch());
}

public function motoristas()
{
    return ((new MotoristasModel())->find("name = :name", "name={$this->motorista}")->fetch());
}

public function cotacoes()
{
    return ((new CotacoesModel())->find("id = :id", "id={$this->cotacao}")->fetch());
}

public function coletas()
{
    return ((new ColetasModel())->find("id = :id", "id={$this->coleta}")->fetch());
}

public function empresas()
{
    return ((new EmpresasModel())->find("name = :name", "name={$this->empresa}")->fetch());
}

}