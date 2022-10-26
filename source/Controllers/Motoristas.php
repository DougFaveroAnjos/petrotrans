<?php


namespace Source\Controllers;


use Source\Facades\EmpresasFacade;
use Source\Facades\MotoristasFacade;
use Source\Models\ColetasModel;
use Source\Models\CotacoesModel;
use Source\Models\EmpresasModel;
use Source\Models\MotoristasModel;
use Source\Models\TransportesModel;

class Motoristas extends Controller
{

    /** @var MotoristasFacade */
    private $new;
    private $edit;
    private $delete;
    private $vincular;

    public function __construct($router)
    {
        parent::__construct($router, "main");
    }

    public function cadastrar(array $data): void
    {

        if($_SESSION["logged"] != true) {
            header("Location:".url());
            return;
        }

        if(isset($_GET['transporte'])) {
            $transporte = (new TransportesModel())->find("id = {$_GET['transporte']}")->fetch();
            $cotacao = (new CotacoesModel())->find("id = :id", "id={$transporte->cotacao_id}")->fetch();
        } else {
            $cotacao = [];
        }

        echo $this->view->render("criarMotorista", [
            "title" => "Cadastrar Motorista",
            "cotacao" => $cotacao
        ]);

    }

    public function editMotorista(array $data): void
    {

        if($_SESSION["logged"] != true) {
            header("Location:".url());
            return;
        }

        $motorista = (new MotoristasModel())->find("id = :id", "id={$data['id']}")->fetch();
        $documentos = explode(" ; ", $motorista->documentos);

        echo $this->view->render("editarMotorista", [
            "title" => "Editar Motorista",
            "motorista" => $motorista,
            "documentos" => $documentos
        ]);
    }

    public function visualizar(array $data): void
    {


        if($_GET['token'] !== "000") {
            $transporte = (new TransportesModel())->find("token = :token", "token={$_GET['token']}")->fetch();
        } else {
            $transporte = (new TransportesModel())->find("id = :id", "id={$data['id']}")->fetch();
        }

        $cotacao = (new CotacoesModel())->find("id = :id", "id={$transporte->cotacao_id}")->fetch();
        $empresa = (new EmpresasModel())->find("name = :name", "name={$transporte->empresa_name}")->fetch();

        if($_GET['token'] !== "000") {
            $motorista = (new MotoristasModel())->find("name = :name", "name={$transporte->motorista_name}")->fetch();
            $documentos = explode(" ; ", $motorista->documentos);
        } else {
            $motorista = [];
            $documentos = [];
        }

        if($transporte->anexos) {
            $comprovantes = explode(" ; ", $transporte->anexos);
        } else {
            $comprovantes = [];
        }

        echo $this->view->render("visualizarMotorista", [
            "title" => "Dados Motorista",
            "transporte" => $transporte,
            "empresa" => $empresa,
            "motorista" => $motorista,
            "cotacao" => $cotacao,
            "documentos" => $documentos,
            "comprovantes" => $comprovantes,
            "anexos" => explode(" ; ", $cotacao->anexos)
        ]);
    }

    public function new(array $data): void
    {

        if((new MotoristasModel())->find("name = :name", "name={$data['name']}")->count() !== 0) {
            setcookie("aviso", "Já existe um motorista com esse nome cadastrado.", time()+8);
            header("Location:".url("motoristas/cadastrar"));
            return;
        }

        $this->new = new MotoristasFacade();

        if(!$this->new->new($data)) {
            echo json_encode("Não foi Possível Cadastrar Motorista no Transporte.");
            header("Location:".url("motoristas/cadastrar"));
            return;
        }

        //LOGGER
        (new \Source\Support\Log("motorista"))
            ->archive()
            ->info("Motorista Cadastrado!");

        echo json_encode("Motorista Cadastrado!");
        header("Location:".url("main/motoristas"));
    }

    public function edit(array $data): void
    {
        $this->edit = new MotoristasFacade();

        if(!$this->edit->edit($data)) {
            header("Location:".url("main/motoristas"));
        }
        //LOGGER
        (new \Source\Support\Log("motorista"))
            ->archive()
            ->info("Motorista Editado!");

        echo "<script>alert('Motorista Editado!!!')</script>";

        header("Location:".url("main/motoristas"));
    }

    public function accept(array $data): void
    {

        $motorista = (new MotoristasModel())->findById($data['id']);
        if($data['accept'] == 'false') {
            $motorista->confirmado = false;
            echo json_encode("Transporte Recusado!");

            //LOGGER
            (new \Source\Support\Log("motorista"))
                ->archive()
                ->info("Transporte Recusado!");
        } else {
            $motorista->confirmado = true;
            echo json_encode("Transporte Confirmado!");

            //LOGGER
            (new \Source\Support\Log("motorista"))
                ->archive()
                ->info("Transporte Confirmado!");
        }

        if(!$motorista->save()) {
            echo json_encode("Erro na Confirmação.");
            return;
        }
    }

    public function delete(array $data): void
    {

        $this->delete = new MotoristasFacade();
        $motorista = (new MotoristasModel())->find("id = :id", "id={$data['id']}")->fetch();

        foreach ((new ColetasModel())->find()->fetch(true) as $coleta) {

            if($coleta->motorista === $motorista->name) {
                echo json_encode("Motorista tem uma coleta vinculada.");
                return;
            }

        }

        if(!$this->delete->delete($data)) {
            echo json_encode("Não foi possível deletar o motorista.");
            return;
        }

        //LOGGER
        (new \Source\Support\Log("motorista"))
            ->archive()
            ->info("Motorista Deletado! ID:{$data["id"]}");


        echo json_encode("Motorista Deletado!");
    }
}