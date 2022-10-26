<?php


namespace Source\Facades;


use CoffeeCode\Uploader\File;
use CoffeeCode\Uploader\Image;
use CoffeeCode\Uploader\Uploader;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use Source\Models\CotacoesModel;
use Source\Models\ContatosModel;
use Source\Models\EmpresasModel;
use Source\Models\TransportesModel;
use Source\Models\Users;

class CotacoesFacade
{

    public function new(CotacoesModel $cotacao, array $data): bool
    {
        if(!(new EmpresasModel())->find("name = :name", "name={$data['empresa']}")->fetch()) {
            $empresa = 1;
        } else { $empresa = (new EmpresasModel())->find("name = :name", "name={$data['empresa']}")->fetch(); }

        $cotacao->vendedor_id = $_SESSION["id"];

        if($data['vendedor'] !== "") {
            $cotacao->vendedor = $data['vendedor'];
        } else {
            $cotacao->vendedor = $_SESSION['name'];
        }

        if(!empty($data['cobranca']) && $data['cobranca'] == 'S'){
            if(!empty($data['cobranca_days'])){
                $cotacao->cobranca = $data['cobranca_days'];
            }else{
                $cotacao->cobranca_data = $data['cobranca_date'];
            }
        }

        $cotacao->cliente_id = $empresa->id;
        $cotacao->cliente = $empresa->name;
        $cotacao->valor_cotacao = $data['valor'];

        if($data['valor_motorista_min'] !== "") {
            $cotacao->valor_motorista_min = $data['valor_motorista_min'];
            $cotacao->valor_motorista_max = $data['valor_motorista_max'];
        }

        $cotacao->valor_nota = $data['nota'];

        if($data['carroceria'] !== "") {
            $cotacao->veiculo = $data['veiculo'] . " -- " . $data['carroceria'];
        } else {
            $cotacao->veiculo = $data['veiculo'];
        }

        if(array_key_exists('cliente_fob', $data)) {
            $cotacao->cliente_fob = $data['cliente_fob'];
        }

        $cotacao->volumes = $data['volumes']." -- ".$data['tipo_mercadoria'];
        $cotacao->peso = $data['peso'];

        if(!empty($data['devolucao']) && $data['devolucao'] != 'n'){
            $cotacao->devolucao = 's';
            $cotacao->devolucao_tipo = $data['devolucao'];
        }

        $cotacao->tipo_carga = $data['tipo'];

        if(array_key_exists("data_coleta", $data)) {
            $cotacao->data_coleta = $data['data_coleta'];
        }

        $cotacao->prazo_previsao = $data['previsao'];
        $cotacao->prazo_maximo = $data['prazo'];
        $cotacao->km = $data['km'];
        $cotacao->opcao_frete = $data['frete'];
        $cotacao->uf_origem = str_replace("'", "", $data['uf-origem']);
        $cotacao->uf_destino = str_replace("'", "", $data['uf-destino']);
        $cotacao->cidade_origem = str_replace("'", "", $data['city-origem']);
        $cotacao->cidade_destino = str_replace("'", "", $data['city-destino']);
        $cotacao->origem_complemento = str_replace("'", "", $data["origem"]);
        $cotacao->destino_complemento = str_replace("'", "", $data["destino"]);
        $cotacao->observacao = str_replace("'", "", $data['obs']);
        $cotacao->date = date("Y-m-d", time());

        if(!array_key_exists("pagamento", $data)) {
            $cotacao->pagamento = $empresa->pagamento;
        } else {
            $cotacao->pagamento = $data['pagamento'];
        }

        if(array_key_exists("lona", $data)) {
            $cotacao->lona = true;
        }

        if(array_key_exists("cintas", $data)) {
            $cotacao->cintas = true;
        }

        if(array_key_exists("acoalho", $data)) {
            $cotacao->acoalho = $data['acoalho-options'];
        }

        if(array_key_exists("ajudantes", $data)) {
            $cotacao->ajudantes = "Coleta:".$data['coleta']." Destino:".$data['destino'];
        }

        if($_FILES['anexos']['name'][0] !== "") {
            $cotacao->anexos = "";

            for ($i = 0; $i < count($_FILES['anexos']['type']); $i++) {
                $imageFiles[$i] = [];

                foreach (array_keys($_FILES['anexos']) as $keys) {
                    $imageFiles[$i][$keys] = $_FILES['anexos'][$keys][$i];
                }
            }

            foreach ($imageFiles as $index => $imageFile) {

                if($index !== 0) {
                    $cotacao->anexos .= " ; ";
                }

                if($imageFile['type'] === "application/pdf") {
                    $image = new File("theme/pdf/cotacoes", $data['empresa']);
                } else {
                    $image = new Image("theme/images/cotacoes", $data['empresa']);
                }

                $upload = $image->upload($imageFile, pathinfo($imageFile['name'], PATHINFO_FILENAME));
                $cotacao->anexos .= url($upload);
            }

        } else { $cotacao->anexos = ""; }


        $result = $cotacao->save();

        if(!$result) {
            return false;
        }


        return true;
    }

    public function delete(CotacoesModel $cotacao): bool
    {

        if(!$cotacao->destroy()) {
            return false;
        }

        return true;
    }

    public function duplicate(CotacoesModel $cotacao): bool
    {
        $new = new CotacoesModel();

        $new->vendedor_id = $cotacao->vendedor_id;
        $new->vendedor = $cotacao->vendedor;
        $new->cliente_id = $cotacao->cliente_id;
        $new->cliente = $cotacao->cliente;
        $new->valor_cotacao = $cotacao->valor_cotacao;
        $new->valor_motorista_min = $cotacao->valor_motorista_min;
        $new->valor_motorista_max = $cotacao->valor_motorista_max;
        $new->valor_nota = $cotacao->valor_nota;
        $new->cliente_fob = $cotacao->cliente_fob;
        $new->veiculo = $cotacao->veiculo;
        $new->volumes = $cotacao->volumes;
        $new->peso = $cotacao->peso;
        $new->tipo_carga = $cotacao->tipo_carga;
        $new->data_coleta = $cotacao->data_coleta;
        $new->prazo_previsao = $cotacao->prazo_previsao;
        $new->prazo_maximo = $cotacao->prazo_maximo;
        $new->km = $cotacao->km;
        $new->opcao_frete = $cotacao->opcao_frete;
        $new->uf_origem = $cotacao->uf_origem;
        $new->uf_destino = $cotacao->uf_destino;
        $new->cidade_origem = $cotacao->cidade_origem;
        $new->cidade_destino = $cotacao->cidade_destino;
        $new->origem_complemento = $cotacao->origem_complemento;
        $new->destino_complemento = $cotacao->destino_complemento;
        $new->observacao = $cotacao->observacao;
        $new->date = $cotacao->date;
        $new->pagamento = $cotacao->pagamento;
        $new->lona = $cotacao->lona;
        $new->cintas = $cotacao->cintas;
        $new->acoalho = $cotacao->acoalho;
        $new->ajudantes = $cotacao->ajudantes;

        if(!$new->save()) {
            return false;
        }

        return true;
    }

    public function update($cotacao, array $data): bool
    {

        $transporte = (new TransportesModel())->find("cotacao_id = :id", "id={$data['cotacao']}");

        if(array_key_exists('status', $data) && !array_key_exists('editar', $data)) {
            $cotacao->status = $data['status'];
        }
        $cotacao->updater = $data['updater'];

        if(array_key_exists('editar', $data) && $data['editar'] === "Editar") {

            if($data['vendedor'] !== "") {
                $cotacao->vendedor = $data['vendedor'];
            }

            if($data['empresa'] !== "") {
                $cotacao->cliente = $data['empresa'];
                $cotacao->cliente_id = (new EmpresasModel())->find("name = :name", "name={$data['empresa']}")->fetch()->id;
            }
//
            if($data['km'] !== "") {
                $cotacao->km = $data['km'];
            }
//
            $cotacao->opcao_frete = $data['frete'];
//
            if($data['city-origem'] !== "") {
                $cotacao->cidade_origem = $data['city-origem'];
            }
            if($data['city-destino'] !== "") {
                $cotacao->cidade_destino = $data['city-destino'];
            }
            if($data['uf-origem'] !== "") {
                $cotacao->uf_origem = $data['uf-origem'];
            }
            if(array_key_exists('cliente_fob', $data)) {
                if($data['cliente_fob'] !== "") {
                    $cotacao->cliente_fob = $data['cliente_fob'];
                }
            }
            if($data['uf-destino'] !== "") {
                $cotacao->uf_destino = $data['uf-destino'];
            }
            if($data['origem'] !== "") {
                $cotacao->origem_complemento = $data['origem'];
            }
            if($data['destino'] !== "") {
                $cotacao->destino_complemento = $data['destino'];
            }
//
            if($data['veiculo'] !== "") {
                if($data['carroceria'] !== "") {
                    $cotacao->veiculo = $data['veiculo'] . " - " . $data['carroceria'];
                } else {
                    $cotacao->veiculo = $data['veiculo'];
                }
            }
//
            if($data['volumes'] !== "") {
                $cotacao->volumes = $data['volumes'];
            }
//
            if($data['peso'] !== "") {
                $cotacao->peso = $data['peso'];
            }
//
            $cotacao->tipo_carga = $data['tipo'];
//
            if(array_key_exists("data_coleta", $data)) {
                $cotacao->data_coleta = $data['data_coleta'];
            }
            if($data['previsao'] !== "") {
                $cotacao->prazo_previsao = $data['previsao'];
            }
            if($data['prazo'] !== "") {
                $cotacao->prazo_maximo = $data['prazo'];
            }
//
            if($data['nota'] !== "") {
                $cotacao->valor_nota = $data['nota'];
            }
            if($data['valor'] !== "") {
                $cotacao->valor_cotacao = $data['valor'];
            }
            if($data['valor_motorista_min'] !== "") {
                $cotacao->valor_motorista_min = $data['valor_motorista_min'];
            }
            if($data['valor_motorista_max'] !== "") {
                $cotacao->valor_motorista_max = $data['valor_motorista_max'];
            }
//
            if(array_key_exists("pagamento", $data)) {
                $cotacao->pagamento = $data['pagamento'];
            }
//
            if($data['obs'] !== "") {
                $cotacao->observacao = $data['obs'];
            }

            $cotacao->cobranca = $data['cobranca'];
            if($_FILES['anexos']['name'][0] !== "") {
                for ($i = 0; $i < count($_FILES['anexos']['type']); $i++) {
                    $imageFiles[$i] = [];

                    foreach (array_keys($_FILES['anexos']) as $keys) {
                        $imageFiles[$i][$keys] = $_FILES['anexos'][$keys][$i];
                    }
                }

                foreach ($imageFiles as $index => $imageFile) {
                    $cotacao->anexos .= " ; ";

                    if($imageFile['type'] === "application/pdf") {
                        $image = new File("theme/pdf/cotacoes", $data['empresa']);
                    } else {
                        $image = new Image("theme/images/cotacoes", $data['empresa']);
                    }
                    $upload = $image->upload($imageFile, pathinfo($imageFile['name'], PATHINFO_FILENAME));
                    $cotacao->anexos .= url($upload);
                }

            }

        }

        $result = $cotacao->save();

        if(!$result) {
            return false;
        }

        return true;
    }
        
        public function mail(array $data): bool
    {
        header('Content-Type: text/html; charset=UTF-8');
        $mails = [];

        if(isset($data['emailunico'])){
	   
	        array_push($mails, $data['emailunico']);
        }
        else{
            if($data['cliente'] !== '') {

                $cliente_fob = (new ContatosModel())->find("empresa = :empresa", "empresa={$data['cliente']}")->fetch(true);
                $mails_fob = [];
                for($i=0; $i<=count($cliente_fob); $i++){
                    if(isset($cliente_fob[$i]) && $cliente_fob[$i]->email !== "") {
                        array_push($mails, $cliente_fob[$i]->email);
                    }
                }
            }

        }  

        array_push($mails, "cotacoes@petrotrans.com.br");
        array_push($mails, "rogerio.diretoria@petrotrans.com.br");

        foreach ($mails as $email) {

            $mail = new PHPMailer();

            $mail->isSMTP();
            $mail->Host = 'smtp.petrotrans.com.br';
            $mail->Port = 587;
            $mail->SMTPSecure = false;
            $mail->SMTPAutoTLS = false;
            $mail->SMTPAuth = true;
            $mail->Username = "comercial=petrotrans.com.br";
            $mail->Password = "171163@rp";
            $mail->setFrom("comercial@petrotrans.com.br", "COTACAO");
            $mail->addAddress($email, $data['name']);
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = '[PETROTRANS] - COTACAO '.$data['id'].' - '. $data['price'] .' x '.$data['destino'];

            $mail->Body = $data['msg'];

            if(!$mail->send()) {
                return false;
            }

            unset($mail);
        }

        $cotacao = (new CotacoesModel())->findById($data['id']);
        $cotacao->enviado = true;
        $cotacao->save();
        return true;
        }
        
        // public function mailbackup(array $data): bool
    // {
    //     header('Content-Type: text/html; charset=UTF-8');

    //     if(!empty($data['email'])) {
    //         $mails = explode("; ", $data['email']);
    //     } else {
    //         $mails = [];
    //     }

    //     if($data['cliente_fob'] !== '') {

    //         $cliente_fob = (new EmpresasModel())->find("name = :name", "name={$data['cliente_fob']}")->fetch();

    //         if(isset($cliente_fob) && $cliente_fob->email !== "") {
    //             $mails_fob = explode("; ", $cliente_fob->email);
    //         }

    //         if(isset($mails_fob)) {
    //             foreach ($mails_fob as $item) {
    //                 array_push($mails, $item);
    //             }
    //         }
    //     }

    //     array_push($mails, "cotacoes@petrotrans.com.br");
    //     array_push($mails, "rogerio.diretoria@petrotrans.com.br");

    //     foreach ($mails as $email) {

    //         $mail = new PHPMailer();

    //         $mail->isSMTP();
    //         $mail->Host = 'smtp.petrotrans.com.br';
    //         $mail->Port = 587;
    //         $mail->SMTPSecure = false;
    //         $mail->SMTPAutoTLS = false;
    //         $mail->SMTPAuth = true;
    //         $mail->Username = "comercial=petrotrans.com.br";
    //         $mail->Password = "171163@rp";
    //         $mail->setFrom("comercial@petrotrans.com.br", "COTACAO");
    //         $mail->addAddress($email, $data['name']);
    //         $mail->isHTML(true);
    //         $mail->CharSet = 'UTF-8';
    //         $mail->Subject = 'COTACAO '.$data['id'].' - '. $data['price'] .': '.$data['destino']. ' [PETROTRANS]';

    //         $mail->Body = $data['msg'];

    //         if(!$mail->send()) {
    //             return false;
    //         }

    //         unset($mail);
    //     }

    //     $cotacao = (new CotacoesModel())->findById($data['id']);
    //     $cotacao->enviado = true;
    //     $cotacao->save();
    //     return true;
    //     }

}
