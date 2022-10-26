<?php


namespace Source\Controllers;


use Source\Facades\ContatoEmpresaFacade;
use Source\Models\ContatosEmpresaModel;
use Source\Models\EmpresasModel;

class ContatoEmpresa extends Controller
{

    /** @var ContatoFacade */
    private $new;
    private $delete;
    private $edit;

    public function __construct($router)
    {
        parent::__construct($router, "main");
    }

    public function criarContatoEmpresa(): void
    {
        if($_SESSION["logged"] != true) {
            header("Location:".url());
            return;
        }

        echo $this->view->render("criarContatoEmpresa", [
            "title" => "Criar ContatoEmpresa",
        ]);
    }

    public function editContatoEmpresa(array $data): void
    {
        if($_SESSION["logged"] != true) {
            header("Location:".url());
            return;
        }

        $contato = (new ContatosEmpresaModel())->find("id_contato_empresa = :id", "id={$data['id']}")->fetch();

        echo $this->view->render("editContato", [
            "title" => "Editar Contato",
            "contato" => $contato
        ]);
    }

    public function new(array $data): void
    {

        $this->new = new ContatoEmpresaFacade();

        if(!$this->new->new($data)) {
            echo $this->ajaxMessage("Ocorreu um Erro ao Criar Contato", "error");
            return;
        }

        //LOGGER
        (new \Source\Support\Log("contato_empresa"))
            ->archive()
            ->info("Contato da Empresa Criado!");

	echo "<script>alert('Contato da Empresa Criado!')</script>";
        //echo $this->ajaxMessage("Contato da Empresa Criado!", "success");

    }

    public function delete(array $data): void
    {
        $this->delete = new ContatoEmpresaFacade();

        if(!$this->delete->delete($data)) {
            echo json_encode("Não foi possível deletar contato.");
            return;
        }
        //LOGGER
        (new \Source\Support\Log("contato_empresa"))
            ->archive()
            ->info("Contato Deletado! ID: {$data["id"]}");

        echo json_encode("Contato Deletado.");
    }

    public function edit(array $data): void
    {
        $this->edit = new ContatoEmpresaFacade();

        if(!$this->edit->edit($data)) {
            echo json_encode("Não foi possível editar contato.");
            return;
        }

        //LOGGER
        (new \Source\Support\Log("contato_empresa"))
            ->archive()
            ->info("Contato Empresa Editado! ID = {$data["id"]}");

        echo json_encode("Contato Empresa Editado.");
    }

    public function search(array $data): void
    {
        $result = array();
        $empresa = (new EmpresasModel())->find("name = :name", "name={$data['empresa']}")->fetch();

        if(!$empresa) {
            $result['existe'] = false;
            echo json_encode($result);
            return;
        }

        $result['existe'] = true;
        $result['responsavel'] = $empresa->responsavel;
        $result['contato'] = $empresa->contato;
        $result['email'] = $empresa->email;
        $result['city'] = $empresa->cidade;
        $result['uf'] = $empresa->estado;
        $result['status'] = $empresa->status;

        echo json_encode($result);

    }
}
