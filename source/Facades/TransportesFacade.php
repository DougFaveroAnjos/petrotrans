<?php


namespace Source\Facades;


use CoffeeCode\Uploader\File;
use CoffeeCode\Uploader\Image;
use Source\Models\ColetasModel;
use Source\Models\ContatosModel;
use Source\Models\CotacoesModel;
use Source\Models\EmpresasModel;
use Source\Models\FiscalModel;
use Source\Models\TransportesModel;
use Source\Support\Whatsapp;

class TransportesFacade
{

    public function new(array $data): bool
    {

        $transporte = new TransportesModel();


        if(array_key_exists("data_liberacao", $data)) {
            $transporte->data_liberacao = $data['data_liberacao'];
        } else {
            $transporte->data_liberacao = "Não Especificado";
        }

        $transporte->data_entrega = "Não Especificado";
        $transporte->cotacao_id = $data['id'];
        $transporte->empresa_name = $data['empresa_name'];
        $transporte->status = $data['status']."; ".$data['color'];
        $transporte->valor_motorista = $data['valor_motorista'];
        $transporte->origem = $data['origem'];
        $transporte->destino = $data['destino'];
        $transporte->carroceria = $data['carroceria'];
        $transporte->tipo_carga	= $data['tipo_carga'];
        $transporte->peso = $data['peso'];
        $transporte->data_coleta = "Não Especificado";

        $transporte->liberacao = 'liberado';

        if(!isset($data['cotacao_id'])) {
            $cotacao = (new CotacoesModel())->findById($data['id']);
            $cotacao->pagamento = $data['pagamento_cotacao'];
            $cotacao->save();
        }

        if(!$transporte->save()) {
            return false;
        }

        return true;
    }

    public function duplicate(array $data): bool
    {
        $modelo = (new TransportesModel())->find("id = :id", "id={$data['id']}")->fetch();

        $transporte = new TransportesModel();

        $transporte->data_liberacao = $modelo->data_liberacao;
        $transporte->cotacao_id = $modelo->cotacao_id;
        $transporte->empresa_name = $modelo->empresa_name;
        $transporte->status = $modelo->status;
        $transporte->valor_motorista = $modelo->valor_motorista;
        $transporte->origem = $modelo->origem;
        $transporte->destino = $modelo->destino;
        $transporte->carroceria = $modelo->carroceria;
        $transporte->tipo_carga	= $modelo->tipo_carga;
        $transporte->peso = $modelo->peso;
        $transporte->data_coleta = $modelo->data_coleta;
        $transporte->liberacao = $modelo->liberacao;

        if(!$transporte->save()) {
            return false;
        }

        return true;
    }

    public function editDimensoes(array $data):bool
    {
        $transporte = (new TransportesModel())->findById($data['id']);

        $transporte->dimensoes = $data['dimensoes'];
        $transporte->endereco_coleta = str_replace("'" , "", $data['enderecocoleta']);
        $transporte->endereco_entrega = str_replace("'" , "", $data['enderecoentrega']);
        $transporte->emails = $data['emails'];
        $transporte->telefones = $data['telefones'];

        if(!$transporte->save()) {
            return false;
        }

        return true;
    }

    public function edit(array $data): bool
    {

        $transporte = (new TransportesModel())->findById($data['id']);

        $transporte->status = $data['status']."; ".$data['color'];

        if(!$transporte->save()) {
            return false;
        }

        return true;
    }

    public function delete(array $data): bool
    {

        $transporte = (new TransportesModel())->findById($data['id']);
        $fiscal = (new FiscalModel())->find("transporte = :id", "id={$data['id']}")->fetch();

        if(!$transporte->destroy() || !$fiscal->destroy()) {
            return false;
        }

        $cotacao = (new CotacoesModel())->findById($transporte->cotacao_id);
        $cotacao->status = "Cancelado";
        $cotacao->save();

        $coleta = (new ColetasModel())->find("transporte = :id", "id={$data['id']}")->fetch();
        $coleta->status = "Cancelado";
        $coleta->save();

        return true;

    }

    public function liberacao(array $data)
    {
        $transporte = (new TransportesModel())->findById($data['id']);
        $cotacao = (new CotacoesModel())->findById($transporte->cotacao_id);
        $wpp = (new Whatsapp());

        $transporte->liberacao = $data['liberacao'];
        if(!empty($transporte->anexos_cte)){
            $xml = simplexml_load_file(url($transporte->anexos_cte));
        }else{
            $xml = new \stdClass();
        }

        switch ($data['liberacao']) {

            case "liberado":
                $transporte->status = "Liberado; #3CB043";
                $wpp->bootstrap($transporte->contato()->contato,"
                Cotação Nº {$transporte->cotacao_id} - Valor {$cotacao->valor_cotacao} - Fechada. Operacional direcionado a enviar um veiculo.")->queue();
                break;

            case "transitando":
                $transporte->status = "Transitando; #6495ED";
                $wpp->bootstrap($transporte->contato()->contato,"Transporte cotação {$cotacao->id} - R$ ".str_price($cotacao->valor_cotacao).", origem {$transporte->origem}, destino {$transporte->destino}, está a caminho.")->queue();
                break;

            case "devolucao":
                $transporte->status = "Devolucao; #52caff";
                //Enviar wpp de devolução
                $wpp->bootstrap($transporte->contato()->contato,"Iniciando processo de devolução de container referente ao CTe numero {$transporte->cte}")->queue();
                break;

            case "finalizado":
                if(!empty($cotacao->devolucao)){
//                    $wpp->bootstrap($transporte->contato()->contato,"Operação de devolução do CTe numero {$transporte->cte} concluida com sucesso dia ".date("d/m/Y").".")->queue();
                    $wpp->bootstrap($transporte->contato()->contato,"Devolução ref. cotação  {$cotacao->id}, valor R$ ".str_price($cotacao->valor_cotacao).", origem {$transporte->origem}, destino {$transporte->destino}, CTe {$transporte->cte}, NFe {$xml->CTe->infCte->infCTeNorm->infDoc->infNFe->chave} está a caminho")->queue();
                }else{
//                    $wpp->bootstrap($transporte->contato()->contato,"Mercadoria origem {$transporte->origem}, destino {$transporte->destino}, cotação {$cotacao->valor_cotacao}, entregue com sucesso dia ".date("d/m/Y").".
//")->queue();
                    $wpp->bootstrap($transporte->contato()->contato,"Transporte cotação {$cotacao->id}, valor R$ ".str_price($cotacao->valor_cotacao).", origem {$transporte->origem}, destino {$transporte->destino}, referente ao CTe {$transporte->cte} e NFe {$xml->CTe->infCte->infCTeNorm->infDoc->infNFe->chave} - Entregue com sucesso ".date("d/m/Y"))->queue();
                }

                if(isset($_FILES['anexos']['name']) && $_FILES['anexos']['name'][0] !== "") {

                    $transporte->anexos = "";

                    for ($i = 0; $i < count($_FILES['anexos']['type']); $i++) {
                        $imageFiles[$i] = [];

                        foreach (array_keys($_FILES['anexos']) as $keys) {
                            $imageFiles[$i][$keys] = $_FILES['anexos'][$keys][$i];
                        }
                    }

                    foreach ($imageFiles as $index => $imageFile) {

                        if($index !== 0) {
                            $transporte->anexos .= " ; ";
                        }

                        if($imageFile['type'] === "application/pdf") {
                            $image = new File("theme/images/transportes", "Transporte-{$data['id']}");
                        } else {
                            $image = new Image("theme/images/transportes", "Transporte-{$data['id']}");
                        }

                        $upload = $image->upload($imageFile, pathinfo($imageFile['name'], PATHINFO_FILENAME));
                        $transporte->anexos .= url($upload);

                    }

                } else { $transporte->anexos = ""; }

                $transporte->data_finalizado = $data['data_finalizado'];

                $fiscal = (new FiscalModel())->find("transporte = :id", "id={$data['id']}")->fetch();

                if($fiscal && isset($fiscal->cte) || isset($fiscal->mdfe)) {
                    $transporte->status = "CTe {$fiscal->cte} / MDFe {$fiscal->mdfe}; #4169E1";
                } else {
                    $transporte->status = "CTe X / MDFe Y; #4169E1";
                }
                break;

        }
        $empresa = (new EmpresasModel())->find("name = :name", "name={$transporte->empresa_name}")->fetch();

        if(!$transporte->save()) {
            $telefones = (new ContatosModel())->find("cnpj = :cnpj", "cnpj={$empresa->cnpj}")->fetch(true);
            //Enviar Whatsapp
            foreach ($telefones as $telefone) {
                $wpp = (new Whatsapp())->bootstrap($telefone->contato, "*Trasnporte finalizado*")->queue();
            }

            return false;
        }

        return true;
    }

    public function editGlobal(array $data): bool
    {

        $transporte = (new TransportesModel())->findById($data['id']);
        $cotacao = (new CotacoesModel())->findById($transporte->cotacao_id);

        $transporte->data_entrega = $data['liberacao'];

        if($data['coleta'] !== "Não Especificado") {
            $transporte->data_liberacao = $data['coleta'];
        }

        $transporte->origem = $data['origem'];
        $transporte->destino = $data['destino'];
        $transporte->ordem = $data['ordem'];
        $cotacao->valor_cotacao = $data['valor'];



        if(!$transporte->save() || !$cotacao->save()) {
            return false;
        }

        return true;
    }

}