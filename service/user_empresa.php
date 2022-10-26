<?php
require __DIR__."/../vendor/autoload.php";

$empresas = (new \Source\Models\EmpresasModel())->find()->fetch(true);

foreach ($empresas as $empresa) {
    if(!empty($empresa->cnpj) &&  deixarNumero($empresa->cnpj) > 0){
        $user = (new \Source\Models\Users())->find("empresa = :id", "id={$empresa->id}")->fetch();
        if(!$user) {
            $user = (new \Source\Models\Users());
            $user->first_name = $empresa->name;
            $user->last_name = $empresa->responsavel;
            $user->email = deixarNumero($empresa->cnpj);
            $user->password = "12345678";
            $user->role = 1;
            $user->status = "approved";
            $user->empresa = $empresa->id;
            $user->save();
        }
    }
}