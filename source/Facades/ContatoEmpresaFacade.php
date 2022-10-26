<?php


namespace Source\Facades;


use DateTime;
use Source\Models\ContatosEmpresaModel;
use Source\Models\EmpresasModel;


class ContatoEmpresaFacade
{
public function new(array $data): bool
{

    $contato = new ContatosEmpresaModel();

    echo 'to aqui';

    $contato->nome = $data['nome'];
    $contato->celular = $data['celular'];
    $contato->email = $data['email'];
    $contato->cargo = $data['cargo'];
    $contato->status = $data['status'];
    $contato->cnpj = $data['cnpjempresa'];

    if(!$contato->save()) {
        return false;
    }
    return true;
}

public function delete(array $data): bool
{

    $contato = (new ContatosEmpresaModel())->findById($data['id_contato_empresa']);

    if(!$contato->destroy()) {
        return false;
    }

    return true;
}

public function edit(array $data): bool
{

    $contato = (new ContatosEmpresaModel())->findById($data['id_contato_empresa']);

    if($data['nome'] !== "") {
        $contato->nome = $data['nome'];
    }
    if($data['cnpj'] !== "") {
        $contato->cnpj = $data['cnpjempresa'];
    }
    if($data['cargo'] !== "") {
        $contato->cargo = $data['cargo'];
    }
    if($data['email'] !== "") {
        $contato->email = $data['email'];
    }
    if($data['celular'] !== "") {
        $contato->celular = $data['celular'];
    }

    if(!$contato->save()) {
        return false;
    }

    return true;
}
}
