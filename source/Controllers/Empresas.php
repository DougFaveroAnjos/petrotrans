<?php


namespace Source\Controllers;


use Source\Facades\EmpresasFacade;
use Source\Models\EmpresasModel;
use Source\Models\ContatosModel;
use Source\Models\Users;

class Empresas extends Controller
{

    /** @var EmpresasFacade */
    private $new;
    private $delete;
    private $edit;

public function __construct($router)
{
    parent::__construct($router, "main");
}

public function criarEmpresa(): void
{
    if($_SESSION["logged"] != true) {
        header("Location:".url());
        return;
    }

    $users = (new Users())->find()->fetch(true);

    echo $this->view->render("criarEmpresa", [
        "title" => "Nova Empresa",
        "users" => $users
    ]);
}

public function editEmpresa(array $data): void
{
    if($_SESSION["logged"] != true) {
        header("Location:".url());
        return;
    }

    $users = (new Users())->find()->fetch(true);

    $empresa = (new EmpresasModel())->find("id = :id", "id={$data['id']}")->fetch();
    
    $cnpj = $empresa->cnpj;
			
    $contatos = (new ContatosModel())->find("cnpj = :cnpj", "cnpj={$cnpj}")->fetch(true);

    echo $this->view->render("editEmpresa", [
        "title" => "Editar Empresa",
        "empresa" => $empresa,
        "users" => $users,
        "contatos" => $contatos
    ]);
}

public function petrotrans(array $data): void
{
    if($_SESSION["logged"] != true) {
        header("Location:".url());
        return;
    }

    $user = (new Users())->find("id = :id", "id={$_SESSION['id']}")->fetch();

    $empresa = (new EmpresasModel())->find("name = :name", "name=PETROTRANS")->fetch();


    echo $this->view->render("petrotrans", [
        "title" => "Editar Petrotrans",
        "empresa" => $empresa,
        "user" => $user
    ]);
}

public function new(array $data): void
{

$this->new = new EmpresasFacade();
$empresa = new EmpresasModel();

$empresas = (new EmpresasModel())->find()->fetch(true);

    foreach ($empresas as $item) {

    if($data['cnpj'] === $item->cnpj) {
        echo json_encode("CNPJ já registrado!");
        return;
    }

    }

if(!$this->new->new($empresa, $data)) {
    echo json_encode("Empresa não Registrada");
    return;
}

//LOGGER
(new \Source\Support\Log("empresa"))
    ->archive()
    ->info("Empresa Registrada");

echo json_encode("Empresa Registrada");
}

public function delete(array $data): void
{

    $this->delete = new EmpresasFacade();

    if(!$this->delete->delete($data)) {
        $_SESSION['message'] = "<div class='message error'>Não Foi Possível Excluir a Empresa</div>";
        echo $_SESSION['message'];
        return;

    }

    //LOGGER
    (new \Source\Support\Log("empresa"))
        ->archive()
        ->info("Empresa excluída com sucesso");

    $_SESSION['message'] = "<div class='message error'>Empresa excluída com sucesso</div>";
    echo $_SESSION['message'];
}

public function edit(array $data): void
{
    $this->edit = new EmpresasFacade();
    $empresa = (new EmpresasModel())->findById($data['id']);

    if(!$this->edit->edit($empresa, $data)) {

        echo json_encode("Não foi possível editar a empresa.");

        if(array_key_exists("petrotrans", $data)) {
            header("Location:".url("main/empresas"));
        }
        return;
    }

    //LOGGER
    (new \Source\Support\Log("empresa"))
        ->archive()
        ->info("Empresa Editada com sucesso");

    echo json_encode("Empresa Editada!");

    if(array_key_exists("petrotrans", $data)) {
        header("Location:".url("main/empresas"));
    }

}

public function search(array $data): void
{

    $empresas = (new EmpresasModel())->find("name like '%{$data['empresa']}%'")->fetch(true);

    $resultName = [];
    $resultPag = [];
    $resultId = [];

    if(!$empresas) {
        $resultName[0] = "Nenhuma Empresa Encontrada";
        $resultPag[0] = "Escolhido pela Empresa";
    } else {
        foreach ($empresas as $key => $empresa) {
            $resultName[$key] = $empresa->name;
            $resultPag[$key] = $empresa->pagamento;
            $resultId[$key] = $empresa->id;
        }
    }

    $result = array();
    $result[0] = $resultName;
    $result[1] = $resultPag;
    $result[2] = $resultId;

    echo json_encode($result);
}

public function consulta(array $data): void
{

    $valor = $data['cnpj'];

    $valor = trim($valor);
    $valor = str_replace(".", "", $valor);
    $valor = str_replace(",", "", $valor);
    $valor = str_replace("-", "", $valor);
    $valor = str_replace("/", "", $valor);

    $dados = file_get_contents("https://www.receitaws.com.br/v1/cnpj/".$valor);

    echo $dados;

}

}
