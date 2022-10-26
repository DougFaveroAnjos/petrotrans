<?php


namespace Source\Controllers;


use PHPMailer\PHPMailer\PHPMailer;
use Source\Facades\AuthFacade;
use Source\Models\Users;

class Auth extends Controller
{
    /** @var AuthFacade */
    private $auth;
    private $mail;

public function __construct($router)
{
    parent::__construct($router);
}

public function auth(array $data):void
{
    $user = new Users();
    $this->auth = new AuthFacade();

    if(!empty($data['type']) && $data['type'] == "client"){

        if(!filter_var($data["password"], FILTER_SANITIZE_STRING)
            || !$user->find("email = :email", "email={$data['email']}")->fetch()
            || !password_verify($data["password"], $user->find("email = :email", "email={$data['email']}")->fetch()->password )
            || !$this->auth->auth($user, $data)
        ) {

            setcookie("errormsg", "<div class='msg error on'>Usuário ou Senha Incorretos</div>", (time() + 3), "/");
            header("Location:".url("cliente"));
            return;

        }

        if($_SESSION['logged'] === true) {

            //LOGGER
            (new \Source\Support\Log("login"))
                ->archive()
                ->info("Cliente logado");

            header("Location:".url("main/transportes?categoria=liberado"));
            return;
        }

    }

    if(!filter_var($data["email"], FILTER_SANITIZE_EMAIL)
        || !filter_var($data["password"], FILTER_SANITIZE_STRING)
        || !filter_var($data["email"], FILTER_VALIDATE_EMAIL)
        || !$user->find("email = :email", "email={$data['email']}")->fetch()
        || !password_verify($data["password"], $user->find("email = :email", "email={$data['email']}")->fetch()->password )
        || !$this->auth->auth($user, $data)
    ) {

        setcookie("errormsg", "<div class='msg error on'>Usuário ou Senha Incorretos</div>", (time() + 3), "/");
        header("Location:".url("sistema"));
        return;

    }

    if($_SESSION['logged'] === true) {
        //LOGGER
        (new \Source\Support\Log("login"))
            ->archive()
            ->info("Admin logado");

        header("Location:".url("main/transportes?categoria=liberado"));
        return;
    }
}

public function recover(array $data): void
{
    $this->mail = new AuthFacade();

    $token = bin2hex(random_bytes(8));

    if(!$this->mail->recover($token, $data)) {
        echo $this->ajaxMessage("ERRO! Email não enviado.", "error");
        return;
    }

    //LOGGER
    (new \Source\Support\Log("login"))
        ->archive()
        ->info("Email enviado!");

    echo $this->ajaxMessage("Email enviado!", "success");
}
}