<?php


namespace Source\Facades;


use DateTime;
use Source\Models\ContatosModel;
use Source\Models\EmpresasModel;
use Source\Models\ColetasModel;


class ContatoFacade
{
    public function new(array $data): bool
    {


        $contato = new ContatosModel();
        $contato->user_name = $_SESSION['name'];
        $contato->empresa = $data['empresa'];
        $contato->responsavel = $data['responsavel'];
        $contato->contato = $data['contato'];
        $contato->email = $data['email'];
        $contato->cidade = $data['city'];
        $contato->estado = $data['uf'];
        $contato->status = $data['status'];
        $contato->cnpj = $data['cnpj'];
        $contato->obs = $data['obs'];
        $contato->date = date("Y-m-d");


       if(!$contato->save()) {
           return false;
       }
       else{
            $cnpj = $data['cnpj'];
            $nome = $data['empresa'];
            $cadastrada = (new EmpresasModel())->find("cnpj = :cnpj", "cnpj={$cnpj}")->fetch(true);
            
            if($cadastrada){
                echo "<script>alert('Empresa $nome já Cadastrada!!')</script>";
            }
            else{

                $nome = $data['empresa'];
                $cadastrada = (new EmpresasModel())->find("name = :name", "name={$nome}")->fetch(true);
                if($cadastrada){
                    echo "<script>alert('Empresa $nome já Cadastrada!!')</script>";
                }
                else{
                    $empresa = new EmpresasModel();
            
                    $empresa->dono_id = $_SESSION['id'];
                $empresa->name = strtoupper(str_replace("'", "", $data['empresa']));
                if($data['responsavel'] !== "") {
                    $empresa->responsavel = $data['responsavel'];
                }
                if($data['email'] !== "") {
                    $empresa->email = $data['email'];
                }

                    $empresa->estado = str_replace("'", "", $data['uf']);
                    $empresa->cidade = str_replace("'", "", $data['city']);
                    $empresa->rua = " ";
                    $empresa->complemento = " ";
            
                    if($data['cnpj'] !== "") {
                        $empresa->cnpj = $data['cnpj'];
                    } else {
                        $empresa->cnpj = "00000000000000";
                    }
                    
                        
                    
                    if($data['contato'] !== "") {
                        $empresa->contato = $data['contato'];
                    }
                    
                        $empresa->pagamento = "A Combinar";
                        $empresa->importante = " ";
                        $empresa->spc = "A Verificar";
                    
                    
                    $empresa->status = $data['status'];

                    $result = $empresa->save();

                    if(!$result) {
                        return false;
                    }
                }

            }

        }

        return true;
    }

    public function delete(array $data): bool
    {

        $contato = (new ContatosModel())->findById($data['id']);

        if(!$contato->destroy()) {
            return false;
        }

        return true;
    }

        public function edit(array $data): bool
    {

        $contato = (new ContatosModel())->findById($data['id']);

        if($data['empresa'] !== "") {
            $contato->empresa = $data['empresa'];
        }
        if($data['responsavel'] !== "") {
            $contato->responsavel = $data['responsavel'];
        }
         if($data['cargo'] !== "") {
            $contato->cargo = $data['cargo'];
        }
        if($data['contato'] !== "") {
            $contato->contato = $data['contato'];
        }
        if($data['city'] !== "") {
            $contato->cidade = $data['city'];
        }
        if($data['uf'] !== "") {
            $contato->estado = $data['uf'];
        }
        if($data['email'] !== "") {
            $contato->email = $data['email'];
        }
        if($data['cnpj'] !== "") {
            $contato->cnpj = $data['cnpj'];
        }
        if($data['obs'] !== "") {
            $contato->obs = $data['obs'];
        }

        $contato->status = $data['status'];

        if(!$contato->save()) {
            return false;
        }

        return true;
    }

    public function mail(array $data, string $path): bool
    {

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
