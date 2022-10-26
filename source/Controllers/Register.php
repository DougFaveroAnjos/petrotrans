<?php


namespace Source\Controllers;

use Source\Facades\RegisterFacade;
use Source\Models\Recovers;
use Source\Models\Users;

class Register extends Controller
{
    /** @var RegisterFacade */
    private $create;
    private $edit;

public function __construct($router)
{
    parent::__construct($router);
}

public function create(array $data): void
{

    $user = new Users();
    $this->create = new RegisterFacade();


    if(!filter_var($data["email"], FILTER_SANITIZE_EMAIL) || !filter_var($data["password"], FILTER_SANITIZE_STRING)) {
        echo $this->ajaxMessage("Ocorreu um erro ao tentar criar a conta.", "error");
        return;
    }

    if($user->find("email = :email", "email={$data['email']}")->count()) {
        echo $this->ajaxMessage("Esse email ja foi registrado", "error");
        return;
    }

    if(!$this->create->create($user, $data)) {
        echo $this->ajaxMessage("Ocorreu um erro ao tentar criar a conta.", "error");
        return;
    }

    //LOGGER
    (new \Source\Support\Log("register"))
        ->archive()
        ->info("Conta criada com sucesso! email: {$data["email"]}");

    echo $this->ajaxMessage("Conta criada com sucesso!", "success");
}

public function edit(array $data): void
{

    $this->edit = new RegisterFacade();

    $user = (new Users())->findById($data['id']);

    if(!filter_var($data["password"], FILTER_SANITIZE_STRING)) {
        echo $this->ajaxMessage("Ocorreu um erro ao tentar editar a conta.", "error");
        return;
    }

    if($data['email'] !== "" && !filter_var($data["email"], FILTER_SANITIZE_EMAIL)) {
            echo $this->ajaxMessage("Ocorreu um erro ao tentar editar a conta.", "error");
            return;
    }

    if(!$this->edit->edit($user, $data)) {
        echo $this->ajaxMessage("Ocorreu um erro ao tentar editar a conta.", "error");
        return;
    }

    //LOGGER
    (new \Source\Support\Log("perfil"))
        ->archive()
        ->info("Conta editada com sucesso!");

    echo $this->ajaxMessage("Conta editada com sucesso!", "success");
}

public function newpass(array $data): void
{

    $this->edit = new RegisterFacade();

    $try = (new Recovers())->find("token = :token", "token={$data['token']}")->fetch();
    $email = $try->email;

    $id = (new Users())->find("email = :email", "email={$email}")->fetch()->id;
    $user = (new Users())->findById($id);

    if(!$this->edit->newpass($data['token'], $user, $data)) {
        echo json_encode("Senha não alterada");
        return;
    }

    //LOGGER
    (new \Source\Support\Log("nova_senha"))
        ->archive()
        ->info("Senha Alterada com Sucesso! email: {$email}");

    echo json_encode("Senha Alterada com Sucesso!");
}
}