<?php


namespace Source\Facades;


use CoffeeCode\Uploader\File;
use CoffeeCode\Uploader\Image;
use CoffeeCode\Uploader\Uploader;
use Example\Models\User;
use PHPMailer\PHPMailer\PHPMailer;
use Source\Models\ContatosModel;
use Source\Models\EmpresasModel;
use Source\Models\FiscalModel;

class FinanceiroFacade
{

    public function add(array $data): bool
    {

        $fiscal = (new FiscalModel())->findById($data['id']);

        if(isset($_FILES['comprovantes'])) {

            for($i = 0; $i < count($_FILES); $i++) {

                if ($i !== 0 || $fiscal->comprovantes) {
                    $fiscal->comprovantes .= " ; ";
                    $fiscal->comprovantes_valores .= " ; ";
                }

                if($i === 0) {
                    $file = "comprovantes";
                    $name = "comprovantes_names";
                    $valor = "comprovantes_valores";
                } else {
                    $file = "comprovantes_".$i;
                    $name = "comprovantes_names_".$i;
                    $valor = "comprovantes_valores_".$i;
                }

                if ($_FILES[$file]['type'] === "application/pdf") {
                    $image = new File("theme/images/financeiro", $fiscal->motoristas()->name);
                } else {
                    $image = new Image("theme/images/financeiro", $fiscal->motoristas()->name);
                }

                $upload = $image->upload($_FILES[$file], $data[$name]);
                $fiscal->comprovantes .= url($upload);
                $fiscal->comprovantes_valores .= $data[$valor];

            }
        }

        $fiscal->comprovantes_descricao = $data['comprovantes_descricao'];

        if(!$fiscal->save()) {
            return false;
        }

        return true;
    }


    public function mail(array $data, string $path): bool
    {

        $contato = (new ContatosModel())->find("email = :email", "email={$data['email']}")->fetch();
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
        $mail->addAddress($data['email'], $contato->responsavel);
        $mail->AddAddress('cotacoes@petrotrans.com.br', 'Cotações');
        $mail->AddAddress('victor.fiscal@petrotrans.com.br', 'Victor');
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $data['assunto'];


        $mail->Body = $data['bosy'];

        $mail->addAttachment($path);

        if(!$mail->send()) {
            return false;
        }

        unset($mail);
        return true;
    }

    public function addBol(array $data): bool
    {

        $fiscal = (new FiscalModel())->findById($data['id']);

        if(isset($_FILES['boletos'])) {

            for($i = 0; $i < count($_FILES); $i++) {

                if ($i !== 0 || $fiscal->boletos) {
                    $fiscal->boletos .= " ; ";
                    $fiscal->boletos_valores .= " ; ";
                    $fiscal->boletos_datas .= " ; ";
                }

                if($i === 0) {
                    $fileA = "boletos";
                    $nameA = "boletos_names";
                    $valorA = "boletos_valores";
                    $dataA = "boletos_datas";
                } else {
                    $fileA = "boletos_".$i;
                    $nameA = "boletos_names_".$i;
                    $valorA = "boletos_valores_".$i;
                    $dataA = "boletos_datas_".$i;
                }

                if ($_FILES[$fileA]['type'] === "application/pdf") {
                    $image = new File("theme/images/financeiro/receber", $fiscal->motoristas()->name);
                } else {
                    $image = new Image("theme/images/financeiro/receber", $fiscal->motoristas()->name);
                }

                $upload = $image->upload($_FILES[$fileA], $data[$nameA]);
                $fiscal->boletos .= $upload;
                $fiscal->boletos_valores .= $data[$valorA];
                $fiscal->boletos_datas .= $data[$dataA];

            }
        }

        $fiscal->boletos_descricao = $data['boletos_descricao'];

        if(!$fiscal->save()) {
            return false;
        }

        return true;
    }

    public function delete(array $data): bool
    {

        $fiscal = (new FiscalModel())->findById($data['id']);

        if($fiscal->comprovantes_historico) {
            $fiscal->comprovantes_historico .= "; ".$fiscal->comprovantes;
        } else {
            $fiscal->comprovantes_historico = $fiscal->comprovantes;
        }

        $fiscal->comprovantes = "";
        $fiscal->comprovantes_valores = "";
        $fiscal->comprovantes_descricao = "";

        if(!$fiscal->save()) {
            return false;
        }

        return true;
    }

    public function deleteBol(array $data): bool
    {

        $fiscal = (new FiscalModel())->findById($data['id']);

        if($fiscal->boletos_historico) {
            $fiscal->boletos_historico .= "; ".$fiscal->boletos;
        } else {
            $fiscal->boletos_historico = $fiscal->boletos;
        }

        $fiscal->boletos = "";
        $fiscal->boletos_valores = "";
        $fiscal->boletos_datas = "";
        $fiscal->boletos_descricao = "";

        if(!$fiscal->save()) {
            return false;
        }

        return true;
    }
}