<?php


namespace Source\Controllers;


use Source\Models\Recovers;
use Source\Models\Users;

class Login extends Controller
{

public function __construct($router)
{
    parent::__construct($router, "login");
}

    public function login(?array $data): void {

        $cliente = [];
        if(!empty($_GET['route']) && $_GET['route'] == "/cliente"){
            $cliente['type'] = "client";
        }

        $_SESSION['logged'] = false;
        unset($_SESSION['id']);

        echo $this->view->render("login", [
            "title" => "Login",
            "dir" => "login",
            "cliente" => $cliente
        ]);

    }
    public function cliente(?array $data): void {

        $cliente = [];
        $cliente['type'] = "client";
        $_SESSION['logged'] = false;
        unset($_SESSION['id']);

        echo $this->view->render("login", [
            "title" => "Login",
            "dir" => "login",
            "cliente" => $cliente
        ]);

    }

    public function register(): void {

        echo $this->view->render("cadastro", [
            "title" => "Cadastro",
            "dir" => "login"
        ]);

    }

    public function logout(): void
    {

        $id = $_SESSION['id'];
        $user = (new Users())->findById($id);
        $_SESSION['logged'] = false;
        unset($_SESSION['name']);
        unset($_SESSION['id']);
        if(!empty($user->empresa)){
            header("Location:".url("cliente"));
            return;
        }
        header("Location:".url("sistema"));
        return;
    }

    public function recover(): void
    {

        echo $this->view->render("recover", [
            "title" => "Recuperar Senha",
            "dir" => "login"
        ]);

    }

    public function newpass(): void
    {

        if(!array_key_exists("token", $_GET)) {
            header("Location:".url());
            return;
        }


        $try = (new Recovers())->find("token = :token", "token={$_GET['token']}")->fetch();


        if(!$try) {
            header("Location:".url());
            return;
        }

        echo $this->view->render("newpass", [
            "title" => "Recuperar Senha",
            "dir" => "login",
            "token" => $_GET['token']
        ]);
    }
}