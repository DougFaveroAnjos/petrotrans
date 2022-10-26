<?php


namespace Source\Controllers;


use Source\Facades\ColetasFacade;
use Source\Facades\FinanceiroFacade;
use Source\Facades\FiscalFacade;
use Source\Models\ContatosModel;
use Source\Models\EmpresasModel;
use Source\Models\FiscalModel;

class Fiscal extends Controller
{

    /** @var FiscalFacade */
    private $delete;
    private $add;
    private $mail;

    public function __construct($router)
    {
        parent::__construct($router, "main");
    }

    public function addD(array $data): void
    {

        if($_SESSION["logged"] != true) {
            header("Location:".url());
            return;
        }

        $fiscal = (new FiscalModel())->find("id = :id", "id={$data['id']}")->fetch();

        echo $this->view->render("addD", [
            "title" => "Adicionar Documentos",
            "fiscal" => $fiscal,
            "arquivoscte" => explode("; ", $fiscal->anexos_cte),
            "arquivosmdfe" => explode("; ", $fiscal->anexos_mdfe),
            "pdfscte" => explode("; ", $fiscal->pdf_cte),
            "pdfsmdfe" => explode("; ", $fiscal->pdf_mdfe),
            "historico" => explode("; ", $fiscal->historico)
        ]);


    }

    public function getMail(?array $data)
    {
        $fiscal = (new FiscalModel())->find("id = :id", "id={$data['id']}")->fetch();

        if(!$fiscal){
            echo json_encode(["msg"=> "<h4>Esse fiscal não existe</h4>"]);
            return;
        }

        if(!$fiscal->boletos){
            echo json_encode(["msg"=> "<p>Não tem nenhum boleto cadastrado clique no link abaixo para adiciona-lo <br><a href='".url("/financeiro/adicionarBoletos/{$data['id']}")."'>Adicionar Boleto</a> </p>"]);
            return;
        }

        $transportes = (new \Source\Models\TransportesModel())->find("id = :n", "n={$fiscal->transporte}")->fetch()->empresa_name;
        $empresa = (new EmpresasModel())->find("name = :n", "n={$transportes}")->fetch();
        $telefones = (new ContatosModel())->find("cnpj = :cnpj", "cnpj={$empresa->cnpj}")->fetch(true);
        $html = null;
        if(!empty($telefones)){
            foreach ($telefones as $telefone) {
                $html .= "<kabel><input type='checkbox' name='email[]' checked value='{$telefone->email}'> {$telefone->responsavel}</kabel>&nbsp&nbsp&nbsp";
            }
        }else{
            $html .= "<label>Não foi encontrado nenhum número de telefone para essa coleta</label>";
        }
        $html .= "<input name='id_fiscal' value='{$fiscal->id}' type='hidden'>";

        echo json_encode(["msg"=> "{$html}"]);
    }


    public function emailSend(array $data)
    {
        $coletaA = (new FiscalModel())->findById($data['id_fiscal']);
        if(!$coletaA){
            echo json_encode(["msg"=>"Fiscal não existe"]);
            return;
        }

        if(!empty($data['email'])){
            $path = "theme/pdf/".$transporte->empresa_name."-".$coleta->id.".pdf";
            $this->mail = new FinanceiroFacade();

            if(!$this->mail->mail($data, $path)) {
                echo json_encode("Não foi possível enviar o email.");
                return;
            }

            //LOGGER
            (new \Source\Support\Log("fiscal"))
                ->archive()
                ->info("Email Enviado! E-mail:{$data["email"]}");

            echo json_encode("Email Enviado.");
        }else{
            echo json_encode(["msg"=>"Escolha pelo menos um numero de telefone"]);
            return;
        }
    }

    public function visualizar(array $data): void
    {
        if($_SESSION["logged"] != true) {
            header("Location:".url());
            return;
        }

        $fiscal = (new FiscalModel())->find("id = :id", "id={$data['id']}")->fetch();

        if($data['type'] === "cte") {
            $arquivo = url($fiscal->anexos_cte);
            $pdf = url($fiscal->pdf_cte);
        } else {
            $arquivo = url($fiscal->anexos_mdfe);
            $pdf = url($fiscal->pdf_mdfe);
        }

        echo $this->view->render("visualizarDocumentos", [
            "title" => "Visualizar Documentos",
            "fiscal" => $fiscal,
            "arquivo" => $arquivo,
            "pdf" => $pdf,
            "historico" => $fiscal->historico,
            "type" => $data['type']
        ]);
    }
    public function add(array $data): void
    {

        $this->add = new FiscalFacade();

        if(!$this->add->add($data)) {
            header("Location:".url("main/fiscal"));
            return;
        }

        //LOGGER
        (new \Source\Support\Log("fiscal"))
            ->archive()
            ->info("Arquivos adicionados");

        header("Location:".url("main/fiscal"));
    }

    public function delete(array $data): void
    {

        $this->delete = new FiscalFacade();

        if(!$this->delete->delete($data)) {
            echo json_encode("Não foi possível deletar a fiscalização.");
            return;
        }

        //LOGGER
        (new \Source\Support\Log("fiscal"))
            ->archive()
            ->info("Deletado com sucesso! ID={$data['id']}");

        echo json_encode("Deletado com sucesso.");
    }

    public function deletedoc(array $data): void
    {

        $this->delete = new FiscalFacade();

        if(!$this->delete->deletedoc($data)) {
            echo json_encode("Não foi possível deletar o documento.");
            return;
        }

        //LOGGER
        (new \Source\Support\Log("fiscal"))
            ->archive()
            ->info("Documento deletado! ID={$data['id']}");

        echo json_encode("Documento deletado.");
    }

}