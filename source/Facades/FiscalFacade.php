<?php


namespace Source\Facades;


use CoffeeCode\Uploader\File;
use CoffeeCode\Uploader\Send;
use CoffeeCode\Uploader\Uploader;
use PHPMailer\PHPMailer\PHPMailer;
use Source\Controllers\Contato;
use Source\Models\ContatosModel;
use Source\Models\FiscalModel;
use Source\Models\TransportesModel;
use Source\Models\Whatsapp;

class FiscalFacade
{

    public function add(array $data): bool
    {

        $fiscal = (new FiscalModel())->findById($data['id']);
        $transporte = (new TransportesModel())->findById($fiscal->transporte);

        if(isset($_FILES["XML-CTe"]['name']) && $_FILES['XML-CTe']['name'][0] !== "") {
            for ($i = 0; $i < count($_FILES["XML-CTe"]['type']); $i++) {
                $cteFiles[$i] = [];

                foreach (array_keys($_FILES['XML-CTe']) as $keys) {
                    $cteFiles[$i][$keys] = $_FILES['XML-CTe'][$keys][$i];
                }
            }

            foreach ($cteFiles as $index => $xmlFile) {

                $filexmlcte = new Send("theme/pdf", "fiscal", ["text/xml"], ["xml"]);
                $uploadxmlcte = $filexmlcte->upload($xmlFile, pathinfo($xmlFile['name'], PATHINFO_FILENAME)."-CTe");

                if($fiscal->anexos_cte) {
                    $fiscal->anexos_cte .= "; ".$uploadxmlcte;
                } else {
                    $fiscal->anexos_cte = $uploadxmlcte;
                }

                $xml = simplexml_load_file($uploadxmlcte);
                if(isset($xml->CTe->infCte->ide->nCT)) {

                    if($fiscal->cte) {
                        $fiscal->cte .= " - ".$xml->CTe->infCte->ide->nCT;
                    } else {
                        $fiscal->cte = $xml->CTe->infCte->ide->nCT;
                    }

                }
                        $transporte->data_liberacao = $xml->CTe->infCte->ide->dhEmi;

            }


        }

        if(isset($_FILES["XML-MDFe"]['name']) && $_FILES['XML-MDFe']['name'][0] !== "") {
            for ($i = 0; $i < count($_FILES["XML-MDFe"]['type']); $i++) {
                $mdfFiles[$i] = [];

                foreach (array_keys($_FILES['XML-MDFe']) as $keys) {
                    $mdfFiles[$i][$keys] = $_FILES['XML-MDFe'][$keys][$i];
                }
            }

            foreach ($mdfFiles as $index => $xmlFile) {

                $filexmlmdfe = new Send("theme/pdf", "fiscal", ["text/xml"], ["xml"]);
                $uploadxmlmdfe = $filexmlmdfe->upload($xmlFile, pathinfo($xmlFile['name'], PATHINFO_FILENAME)."-MDFe");

                if($fiscal->anexos_mdfe) {
                    $fiscal->anexos_mdfe .= "; ".$uploadxmlmdfe;
                } else {
                    $fiscal->anexos_mdfe = $uploadxmlmdfe;
                }

                $xml = simplexml_load_file($uploadxmlmdfe);
                if(isset($xml->MDFe->infMDFe->ide->nMDF)) {

                    if($fiscal->mdfe) {
                        $fiscal->mdfe .= " - ".$xml->MDFe->infMDFe->ide->nMDF;
                    } else {
                        $fiscal->mdfe = $xml->MDFe->infMDFe->ide->nMDF;
                    }

                }
            }
        }

        if(isset($_FILES["PDF-CTe"]['name']) && $_FILES["PDF-CTe"]['name'][0] !== "") {
            for ($i = 0; $i < count($_FILES["PDF-CTe"]['type']); $i++) {
                $ctePDFS[$i] = [];

                foreach (array_keys($_FILES["PDF-CTe"]) as $keys) {
                    $ctePDFS[$i][$keys] = $_FILES["PDF-CTe"][$keys][$i];
                }
            }

            foreach ($ctePDFS as $index => $pdfFile) {
                $filepdfcte = new File("theme/pdf", "fiscal");
                $uploadpdfcte = $filepdfcte->upload($pdfFile, pathinfo($pdfFile['name'], PATHINFO_FILENAME)."-CTe");

                if($fiscal->pdf_cte) {
                    $fiscal->pdf_cte .= " ; ".$uploadpdfcte;
                } else {
                    $fiscal->pdf_cte = $uploadpdfcte;
                }
            }
        }

        if(isset($_FILES["PDF-MDFe"]['name']) && $_FILES["PDF-MDFe"]['name'][0] !== "") {
            for ($i = 0; $i < count($_FILES["PDF-MDFe"]['type']); $i++) {
                $mdfPDFS[$i] = [];

                foreach (array_keys($_FILES["PDF-MDFe"]) as $keys) {
                    $mdfPDFS[$i][$keys] = $_FILES["PDF-MDFe"][$keys][$i];
                }
            }

            foreach ($mdfPDFS as $index => $pdfFile) {
                $filepdfmdf = new File("theme/pdf", "fiscal");
                $uploadpdfmdf = $filepdfmdf->upload($pdfFile, pathinfo($pdfFile['name'], PATHINFO_FILENAME)."-MDFe");

                if($fiscal->pdf_mdfe) {
                    $fiscal->pdf_mdfe .= " ; ".$uploadpdfmdf;
                } else {
                    $fiscal->pdf_mdfe = $uploadpdfmdf;
                }
            }
        }

        if($data['no_cte']) {
            $fiscal->cte = "N/A";
        }

        if($data['no_mdfe']) {
            $fiscal->mdfe = "N/A";
        }

        if(explode("; ", $transporte->status)[0] === "Transitando") {
            $transporte->status = "Transitando - CTe {$fiscal->cte} / MDFe {$fiscal->mdfe}; #6495ED";
        } else {
            $transporte->status = "CTe {$fiscal->cte} / MDFe {$fiscal->mdfe}; #4169E1";
        }
        $transporte->cte = $fiscal->cte;
        $transporte->mdfe = $fiscal->mdfe;
        $transporte->save();

        $xml = simplexml_load_file(url($transporte->anexos_cte));

        (new \Source\Support\Whatsapp())->bootstrap($transporte->contato()->contato,"Transporte cotação {$transporte->cotacao()->id}, valor R$ ".str_price($transporte->cotacao()->valor_cotacao).", origem {$transporte->origem}, destino {$transporte->destino}, referente ao CTe {$transporte->cte} e NFe {$xml->CTe->infCte->infCTeNorm->infDoc->infNFe->chave} - está manifestado e a caminho do destino.")->queue();

        if(!$fiscal->save()) {
            return false;
        }

        return true;
    }

    public function delete(array $data): bool
    {

        $fiscal = (new FiscalModel())->findById($data['id']);

        if(!$fiscal->destroy()) {
            return false;
        }

        return true;
    }

    public function deletedoc(array $data): bool
    {

        $fiscal = (new FiscalModel())->findById($data['id']);
        $transporte = (new TransportesModel())->findById($fiscal->transporte);

        if($data['type'] === "cte") {
            if($data['base'] === "xml") {
                $anexos = explode("; ", $fiscal->anexos_cte);
                $cte = explode(" - ", $fiscal->cte);
                $cteT = explode(" - ", $transporte->cte);

                foreach ($anexos as $index => $anexo) {
                    if($anexo === $data['link']) {
                        unset($anexos[$index]);
                        unset($cte[$index]);
                        unset($cteT[$index]);
                    }
                }

                $fiscal->anexos_cte = implode("; ", $anexos);
                $fiscal->cte = implode(" - ", $cte);
                $transporte->cte = implode(" - ", $cteT);

                if($fiscal->historico) {
                    $fiscal->historico .= "; ".$data['link'];
                } else {
                    $fiscal->historico = $data['link'];
                }
            }

            if($data['base'] === "pdf") {
                $anexos = explode("; ", $fiscal->pdf_cte);

                foreach ($anexos as $index => $anexo) {
                    if($anexo === $data['link']) {
                        unset($anexos[$index]);
                    }
                }

                $fiscal->pdf_cte = implode("; ", $anexos);

                if($fiscal->historico) {
                    $fiscal->historico .= "; ".$data['link'];
                } else {
                    $fiscal->historico = $data['link'];
                }
            }
        }

        if($data['type'] === "mdfe") {
            if($data['base'] === "xml") {
                $anexos = explode("; ", $fiscal->anexos_mdfe);
                $mdfe = explode(" - ", $fiscal->mdfe);
                $mdfeT = explode(" - ", $transporte->mdfe);

                foreach ($anexos as $index => $anexo) {
                    if($anexo === $data['link']) {
                        unset($anexos[$index]);
                        unset($mdfe[$index]);
                        unset($mdfeT[$index]);
                    }
                }

                $fiscal->anexos_mdfe = implode("; ", $anexos);
                $fiscal->mdfe = implode(" - ", $mdfe);
                $transporte->mdfe = implode(" - ", $mdfeT);

                if($fiscal->historico) {
                    $fiscal->historico .= "; ".$data['link'];
                } else {
                    $fiscal->historico = $data['link'];
                }
            }

            if($data['base'] === "pdf") {
                $anexos = explode("; ", $fiscal->pdf_mdfe);

                foreach ($anexos as $index => $anexo) {
                    if($anexo === $data['link']) {
                        unset($anexos[$index]);
                    }
                }

                $fiscal->pdf_mdfe = implode("; ", $anexos);

                if($fiscal->historico) {
                    $fiscal->historico .= "; ".$data['link'];
                } else {
                    $fiscal->historico = $data['link'];
                }
            }
        }


        if(!$fiscal->save()) {
            return false;
        }

        return true;
    }

    public function mail(array $data, string $path): bool
    {
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
        $mail->setFrom("comercial@petrotrans.com.br", "COTACAO");
        $mail->addAddress($data['email'], $data['name']);
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = '[PETROTRANS] - FISCAL '.$data['id'];

        $mail->Body = $data['msg'];

        $mail->addAttachment($path, "Cte.pdf");

        if(!$mail->send()) {
            return false;
        }
        return true;
    }
}