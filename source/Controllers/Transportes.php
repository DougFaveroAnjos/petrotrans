<?php


namespace Source\Controllers;


use Source\Facades\TransportesFacade;
use Source\Models\CotacoesModel;
use Source\Models\TransportesModel;

class Transportes extends Controller
{

    /** @var TransportesFacade */
    private $new;
    private $edit;
    private $delete;
    private $duplicate;

    public function __construct($router)
    {
        parent::__construct($router, "main");
    }

    public function finalizar(array $data): void
    {

        if($_SESSION["logged"] != true) {
            header("Location:".url());
            return;
        }

        $transporte = (new TransportesModel())->find("id = :id", "id={$data['id']}")->fetch();

        echo $this->view->render("finalizarTransporte", [
           "title" => "Finalizar Transporte",
           "transporte" => $transporte,
        ]);

    }

    public function devolucao(array $data): void
    {

        if($_SESSION["logged"] != true) {
            header("Location:".url());
            return;
        }

        $transporte = (new TransportesModel())->find("id = :id", "id={$data['id']}")->fetch();

        echo $this->view->render("devolucaoTransporte", [
            "title" => "Finalizar Transporte",
            "transporte" => $transporte,
        ]);

    }

    public function new(array $data): void
    {

        $this->new = new TransportesFacade();

        if(isset($data['cotacao_id'])) {
            $cotacao = (new CotacoesModel())->find("id = :id", "id={$data['cotacao_id']}")->fetch();

            $data['id'] = $data['cotacao_id'];
            $data['empresa_name'] = $cotacao->cliente;
            $data['status'] = "Aguardando Liberação";
            $data['color'] = "#E3AE26";
            $data['valor_motorista'] = "Entre: R$".number_format($cotacao->valor_motorista_min,2,",",".")." E: R$".number_format($cotacao->valor_motorista_max,2,",",".");
            $data['origem'] = $cotacao->cidade_origem . "/" . $cotacao->uf_origem;
            $data['destino'] = $cotacao->cidade_destino . "/" . $cotacao->uf_destino ;
            $data['carroceria'] = $cotacao->veiculo;
            $data['tipo_carga'] = $cotacao->tipo_carga;
            $data['peso'] = $cotacao->peso;

        }

        if(!$this->new->new($data)) {
            echo json_encode("Não Foi Possível Registrar Transporte.") ;
            return;
        }

        //LOGGER
        (new \Source\Support\Log("transporte"))
            ->archive()
            ->info("Transporte cadastrado");

        echo json_encode("Transporte Registrado!");
    }

    public function confirm(array $data): void
    {

        $transporte = (new TransportesModel())->findById($data['id']);

        if($data['confirmado'] === "true") {
            $transporte->confirmado = true;
        } else {
            $transporte->confirmado = false;
        }


        if(!$transporte->save()) {
            echo json_encode("Ocorreu um erro.");
            return;
        }

        //LOGGER
        (new \Source\Support\Log("transporte"))
            ->archive()
            ->info("Alteração de confirmação alterada");

        echo json_encode("Confirmação Alterada");
    }

    public function refresh(array $data): void
    {

        $transporte = (new TransportesModel())->findById($data['id']);

        $cotacao = (new CotacoesModel())->find("id = :id", "id={$transporte->cotacao_id}")->fetch();

        $transporte->empresa_name = $cotacao->cliente;
        $transporte->origem = $cotacao->cidade_origem."/".$cotacao->uf_origem;
        $transporte->destino = $cotacao->cidade_destino."/".$cotacao->uf_destino;
        $transporte->carroceria = $cotacao->veiculo;
        $transporte->tipo_carga = $cotacao->tipo_carga;
        $transporte->peso = $cotacao->peso;

        if(!$transporte->save()) {
            echo json_encode("Ocooreu um erro.");
            return;
        }

        //LOGGER
        (new \Source\Support\Log("transporte"))
            ->archive()
            ->info("Transporte atualizado");

        echo json_encode("Transporte atualizado.");
    }

    public function duplicate(array $data): void
    {

        $this->duplicate = new TransportesFacade();

        if(!$this->duplicate->duplicate($data)) {
            echo json_encode("Não foi possível duplicar transporte.");
        }

        //LOGGER
        (new \Source\Support\Log("transporte"))
            ->archive()
            ->info("Transporte duplicado");

        echo json_encode("Transporte duplicado.");
    }

    public function editDimensoes(array $data): void
    {
        $this->edit = new TransportesFacade();

        if(!$this->edit->editDimensoes($data)) {
            echo json_encode("Não Foi Possivel Adicionar as Dimensões.");
            return;
        }

        //LOGGER
        (new \Source\Support\Log("transporte"))
            ->archive()
            ->info("Dimensões Adicionadas");

        echo json_encode("Dimensões Adicionadas!");

    }

    public function edit(array $data): void
    {

        $this->edit = new TransportesFacade();

        if(!$this->edit->edit($data)) {
            echo json_encode("Não Foi Possivel Editar o Status.");
            return;
        }

        //LOGGER
        (new \Source\Support\Log("transporte"))
            ->archive()
            ->info("Status Alterado!");

        echo json_encode("Status Alterado!");
    }

    public function delete(array $data): void
    {

        $this->delete = new TransportesFacade();

        if(!$this->delete->delete($data)) {
            echo json_encode("Não foi possível deletar o transporte.");
            return;
        }

        //LOGGER
        (new \Source\Support\Log("transporte"))
            ->archive()
            ->info("Transporte deletado!");
        echo json_encode("Transporte deletado!");
    }

    public function liberacao(array $data): void
    {

        $this->edit = new TransportesFacade();

        if(!$this->edit->liberacao($data)) {
            if($data['liberacao'] === "finalizado") {
                header("Location:".url("main/transportes?categoria=finalizado"));
                return;
            }

            echo json_encode("Não foi possível alterar o transporte de lista.");
            return;
        }

        if($data['liberacao'] === "finalizado") {
            header("Location:".url("main/transportes?categoria=finalizado"));
            return;
        }

        //LOGGER
        (new \Source\Support\Log("transporte"))
            ->archive()
            ->info("Transporte alterado para: {$data['liberacao']}!");
        echo json_encode("Transporte alterado!");
    }

    public function editGlobal(array $data): void
    {

        $this->edit = new TransportesFacade();

        if(!$this->edit->editGlobal($data)) {
            echo json_encode("Não foi possível editar o transporte.");
            return;
        }

        //LOGGER
        (new \Source\Support\Log("transporte"))
            ->archive()
            ->info("Transporte Editado!");
        echo json_encode("Transporte Editado.");
    }
}