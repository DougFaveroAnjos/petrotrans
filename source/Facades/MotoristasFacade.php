<?php


namespace Source\Facades;


use CoffeeCode\Uploader\File;
use CoffeeCode\Uploader\Image;
use CoffeeCode\Uploader\Uploader;
use Source\Models\ColetasModel;
use Source\Models\MotoristasModel;
use Source\Models\TransportesModel;

class MotoristasFacade
{

    public function new(array $data): bool
    {

        $motorista = new MotoristasModel();

        $motorista->buonny = $data['buonny'];
        $motorista->name = $data['name'];
        $motorista->cpf = $data['cpf'];
        $motorista->telefone = $data['telefone'];
        $motorista->placa_veiculo = $data['placa_veiculo'];
        $motorista->placa_reboque = $data['placa_reboque'];
        $motorista->carroceria = $data['carroceria'];
        $motorista->modelo = $data['modelo'];
        $motorista->valor = $data['valor'];
        $motorista->pagamento = $data['pagamento'];
        $motorista->obs = str_replace("'", "", $data['obs']);
        $motorista->pix = $data['pix'];
        $motorista->agencia = $data['agencia'];
        $motorista->conta = $data['conta'];
        $motorista->banco = $data['banco'];
        $motorista->nome_banco = $data['nome_banco'];
        $motorista->cpf_banco = $data['cpf_banco'];

        if(isset($data['desconto_check'])) {
            $motorista->desconto = $data['desconto'];
            $motorista->motivo_desconto = $data['motivo_desconto'];
        }

        if(array_key_exists("adiantamento", $data)) {
            $motorista->porcentagem = $data['adiantamento'];
        }
        if(array_key_exists("vista", $data)) {
            $motorista->vista = $data['vista'];
        }
        if(array_key_exists("cheque", $data)) {
            $motorista->cheque = $data['cheque'];
        }

        if(isset($_FILES['documentos']['name']) && $_FILES['documentos']['name'][0] !== "") {
            $motorista->documentos = "";

            for ($i = 0; $i < count($_FILES['documentos']['type']); $i++) {
                $imageFiles[$i] = [];

                foreach (array_keys($_FILES['documentos']) as $keys) {
                   $imageFiles[$i][$keys] = $_FILES['documentos'][$keys][$i];
                }
            }

            foreach ($imageFiles as $index => $imageFile) {

                if($index !== 0) {
                    $motorista->documentos .= " ; ";
                }

                if($imageFile['type'] === "application/pdf") {
                    $image = new File("theme/images/motoristas", $data['name']);
                } else {
                    $image = new Image("theme/images/motoristas", $data['name']);
                }

                $upload = $image->upload($imageFile, pathinfo($imageFile['name'], PATHINFO_FILENAME));
                $motorista->documentos .= url($upload);

            }

        } else { $motorista->documentos = ""; }

        if(!$motorista->save()){
            return false;
        }

        return true;
    }

    public function edit(array $data): bool
    {

        $motorista = (new MotoristasModel())->findById($data['id']);

        $motorista->buonny = $data['buonny'];

        if((new ColetasModel())->find("motorista = :motorista", "motorista={$motorista->name}")->count()) {

            $coleta = (new ColetasModel())->find("motorista = :motorista", "motorista={$motorista->name}")->fetch();
            $coleta->motorista = $data['name'];

            $coleta->save();
        }

        if((new TransportesModel())->find("motorista_name = :motorista", "motorista={$motorista->name}")->count()) {

            $transporte = (new TransportesModel())->find("motorista_name = :motorista", "motorista={$motorista->name}")->fetch();
            $transporte->motorista_name = $data['name'];

            $transporte->save();

        }
        $motorista->name = $data['name'];
        $motorista->cpf = $data['cpf'];
        $motorista->telefone = $data['telefone'];
        $motorista->placa_veiculo = $data['placa_veiculo'];
        $motorista->placa_reboque = $data['placa_reboque'];
        $motorista->carroceria = $data['carroceria'];
        $motorista->modelo = $data['modelo'];
        $motorista->obs = str_replace("'", "", $data['obs']);
        $motorista->pix = $data['pix'];
        $motorista->agencia = $data['agencia'];
        $motorista->conta = $data['conta'];
        $motorista->banco = $data['banco'];
        $motorista->nome_banco = $data['nome_banco'];
        $motorista->cpf_banco = $data['cpf_banco'];

  //      $motorista->valor = $data['valor'];
        $motorista->pagamento = $data['pagamento'];

        switch ($data['pagamento']) {

            case "adiantamento":

                $motorista->porcentagem = $data['texto_pagamento'];

                break;

            case "vista":

                $motorista->vista = $data['texto_pagamento'];

                break;

            case "cheque":

                $motorista->cheque = $data['texto_pagamento'];

                break;

        }

        if(!$motorista->save()){
            return false;
        }

        return true;
    }

    public function delete(array $data): bool
    {

        $motorista = (new MotoristasModel())->findById($data['id']);

        $documentos = explode(" ; ", $motorista->documentos);
        $lenght = strlen(url());

        foreach ($documentos as $documento) {

            $fileToDelete = substr($documento, $lenght+1);

            if(file_exists($fileToDelete) && is_file($fileToDelete)) {
                unlink($fileToDelete);
            }
        }

        if($motorista->transporte_id !== null) {
            return false;
        }

        if(!$motorista->destroy()) {
            return false;
        }

        return true;
    }

    public function vincular(array $data): bool
    {

        $transporte = (new TransportesModel())->findById($data['id']);


        $transporte->motorista_name = $data['motorista'];
        $transporte->token = bin2hex(random_bytes(16));

       if($data['motorista'] !== "motorista") {
           $coleta = new ColetasModel();
           $coleta->transporte = $data['id'];
           $coleta->motorista = $data['motorista'];
           $coleta->date = date("Y-m-d", time());

           if(!$coleta->save()) {
            return false;
        }
       }

        if(!$transporte->save()) {
            return false;
        }

        return true;
    }
}