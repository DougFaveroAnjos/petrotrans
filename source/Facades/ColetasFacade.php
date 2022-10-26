<?php


namespace Source\Facades;


use PHPMailer\PHPMailer\PHPMailer;
use Source\Models\ColetasModel;
use Source\Models\FiscalModel;
use Source\Models\ContatosModel;
use Source\Models\MotoristasModel;
use Source\Models\TransportesModel;
use Source\Models\EmpresasModel;
use Source\Models\Users;

class ColetasFacade
{

    public function new(array $data): bool
    {

        $coleta = new ColetasModel();


        $coleta->transporte = $data['transporte_id'];
        $coleta->coleta_id = (new TransportesModel())->find("id = :id", "id={$data['transporte_id']}")->fetch()->cotacao_id;
        $coleta->valor = $data['valor'];
        $coleta->desconto = $data['desconto'];
        $coleta->motivo_desconto = $data['motivo_desconto'];
        $coleta->pagamento = $data['pagamento'];

        if(!isset($data['name'])) {
            $coleta->motorista = $data['motoristas'];
            $motorista = (new MotoristasModel())->find("name = :name", "name={$data['motoristas']}")->fetch();
        } else {
            $coleta->motorista = $data['name'];
            $motorista = (new MotoristasModel())->find("name = :name", "name={$data['name']}")->fetch();
        }

        $coleta->placa_veiculo = $motorista->placa_veiculo;
        $coleta->placa_reboque = $motorista->placa_reboque;
        $coleta->status = "Normal";
        $coleta->endereco_coleta = $data['endereco_coleta'];
        $coleta->endereco_entrega = $data['endereco_entrega'];
        $coleta->emails = $data['emails'];
        $coleta->telefones = $data['telefones'];
        $coleta->date = (new \DateTime())->format('d/m/Y');
        $coleta->obs = $data['obs_oc'];

        //--------------------------------------------------------------------------------//

        $transporte = (new TransportesModel())->findById($data['transporte_id']);

        if(!isset($data['name'])) {
            $transporte->motorista_name = $data['motoristas'];
        } else {
            $transporte->motorista_name = $data['name'];
        }

        if($data['coleta'] !== "") {
            $transporte->data_liberacao = $data['coleta'];
        }
        $transporte->token = bin2hex(random_bytes(16));
        $transporte->liberacao = "transitando";
        $transporte->status = "Transitando; #6495ED";


        //--------------------------------------------------------------------------------//

        if(!$coleta->save() || !$transporte->save()) {
            return false;
        }

        //--------------------------------------------------------------------------------//

        $fiscal = new FiscalModel();

        $fiscal->transporte = $data['transporte_id'];
        $fiscal->origem = $transporte->origem;
        $fiscal->destino = $transporte->destino;
        $fiscal->cotacao = $transporte->cotacao_id;
        $fiscal->coleta = (new ColetasModel())->find("transporte = :transporte", "transporte={$data['transporte_id']}")->fetch()->id;
        $fiscal->empresa = $transporte->empresa_name;

        if(!isset($data['name'])) {
            $fiscal->motorista = $data['motoristas'];
        } else {
            $fiscal->motorista = $data['name'];
        }

        if(!$fiscal->save()){
            return false;
        }

        return true;
    }

    public function edit(array $data): bool
    {

        $coleta = (new ColetasModel())->findById($data['id']);
        $transporte = (new TransportesModel())->findById($coleta->transporte);

        $coleta->motorista = $data['motorista'];
        $transporte->motorista_name = $data['motorista'];

        $motorista = (new MotoristasModel())->find("name = :name", "name={$data['motorista']}")->fetch();
        $motorista->valor = $data['valor'];

        if($data['coleta'] !== "") {
            $transporte->data_liberacao = $data['coleta'];
        }
        $coleta->valor = $data['valor'];
        $coleta->desconto = $data['desconto'];
        $coleta->motivo_desconto = $data['motivo_desconto'];
        $coleta->pagamento = $data['pagamento'];
	
        $coleta->endereco_coleta = $data['endereco_coleta'];
        $coleta->endereco_entrega = $data['endereco_entrega'];
        $coleta->emails = $data['emails'];
        $coleta->telefones = $data['telefones'];
        $coleta->obs = $data['obs_oc'];

        if(!$coleta->save() || !$transporte->save() || !$motorista->save()) {
            return false;
        }

        return true;
    }

    public function mail(array $data, string $path): bool
    {
        if(isset($data['emailapresentacao'])){

            $id = 39;
            $empresa = (new EmpresasModel())->find("id = :id", "id={$id}")->fetch();
            $user = (new Users())->find("id = :id", "id={$_SESSION['id']}")->fetch();

            $email = $data['emailapresentacao'];
            $nome = $data['nomecontato'];

            $contato = (new ContatosModel())->find("email = :email", "email={$email}")->fetch();

            header('Content-Type: text/html; charset=UTF-8');

            $mail = new PHPMailer();

            $mail->isSMTP();
            $mail->Host = 'smtp.petrotrans.com.br';
            $mail->Port = 587;
            $mail->SMTPSecure = false;
            $mail->SMTPAutoTLS = false;
            $mail->SMTPAuth = true;
            $mail->Username = "comercial=petrotrans.com.br";
            $mail->Password = "171163@rp";
            $mail->setFrom("comercial@petrotrans.com.br", "Apresentação");
            $mail->addAddress($email, $nome);
            $mail->AddAddress('cotacoes@petrotrans.com.br', 'Cotações');
            $mail->AddAddress('victor.fiscal@petrotrans.com.br', 'Victor');
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = $user->assunto;

            
            $mail->Body = $user->apresentacao."<br><br>  <img src='".$user->assinatura."' alt='Assinatura'>";
            $mail->Body = str_replace('[NOMEEMPRESA]', $contato->empresa, $mail->Body);
            $mail->Body = str_replace('[NOME]', $nome, $mail->Body);

            $mail->addAttachment($path);

            if(!$mail->send()) {
                return false;
            }

            unset($mail);
            return true;
        }
        else{
            $coleta = (new ColetasModel())->find('id = :id', "id={$data['id']}'")->fetch();
            $transporte = (new TransportesModel())->find("id = :id", "id={$coleta->transporte}")->fetch();
            $motorista = (new MotoristasModel())->find("name = :name", "name={$transporte->motorista_name}")->fetch();

            header('Content-Type: text/html; charset=UTF-8');

            if(!empty($coleta->emails)) {
                $mails = explode("; ", $coleta->emails);
            } else {
                $mails = [];
            }

            array_push($mails, "cotacoes@petrotrans.com.br");
            array_push($mails, "victor.fiscal@petrotrans.com.br");

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
                $mail->setFrom("comercial@petrotrans.com.br", "Ordem de Coleta");
                $mail->addAddress($email, $transporte->empresa_name);
                $mail->AddAddress('cotacoes@petrotrans.com.br', 'Cotações');
                $mail->AddAddress('victor.fiscal@petrotrans.com.br', 'Victor');
                $mail->isHTML(true);
                $mail->CharSet = 'UTF-8';
                $mail->Subject = 'ORDEM DE COLETA PETROTRANS – Origem: '.$transporte->origem.'x Destino: '.$transporte->destino.'.';

                $mail->Body = "
                    Boa tarde Srs, 
                    <br/>
                    <br/>
                    Por gentileza, liberar a coleta do motorista que deve chegar hoje {$transporte->data_liberacao} referente a coleta da empresa {$transporte->empresa_name}.
                    <br/>    
                    <br/>
                        Segue dados do veículo, caso haja necessidade: <br/>
                    ·       Motorista: {$transporte->motorista_name} <br/>
                    ·       CPF: {$motorista->cpf} <br/>
                    ·       Carroceria: {$motorista->carroceria}. <br/>
                    ·       Placa veiculo: {$motorista->placa_veiculo} <br/>
                    ·       Placa reboque: {$motorista->placa_reboque} <br/>
                    ·       Modelo/Ano: {$motorista->modelo} <br/>
                        <br/>
                    <br/>
                    <br/>
                    <img src='https://www.petrotrans.com.br/sistema/theme/images/assinatura.png' alt='Assinatura'>
                ";
                $mail->addAttachment($path, $transporte->empresa_name."-".$coleta->id.".pdf");

                if(!$mail->send()) {
                    return false;
                }

                unset($mail);
            }

            $coleta = (new ColetasModel())->findById($data['id']);
            $coleta->email = true;
            $coleta->save();

            unlink($path);
            return true;
        }
    }

    public function delete(array $data): bool
    {

        $coleta = (new ColetasModel())->findById($data['id']);

        if(!$coleta->destroy()) {
            return false;
        }
        return true;
    }

}
