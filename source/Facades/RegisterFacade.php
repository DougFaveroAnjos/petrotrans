<?php


namespace Source\Facades;


use Source\Models\Recovers;
use Source\Models\Users;

class RegisterFacade
{
    public function __construct()
    {
        if (!session_id()) {
            session_start();
            $_SESSION['logged'] = false;
        }
    }

    public function create(Users $user, array $data): bool {
        $user->first_name = $data["first_name"];
        $user->last_name = $data["last_name"];
        $user->email = $data["email"];
        $user->password = $data["password"];
        $user->role = $data['role'];

        $result = $user->save();

        if(!$result) {
            return false;
        }

        return true;
    }

    public function edit($user, array $data): bool
    {

        if(array_key_exists("first_name", $data) && $data['first_name'] !== "") {
            $user->first_name = $data["first_name"];
        }
        if(array_key_exists("last_name", $data) && $data['last_name'] !== "") {
            $user->last_name = $data["last_name"];
        }

        if(array_key_exists("email", $data) && $data['email'] !== "") {
            $user->email = $data["email"];
        }

        if(array_key_exists("password", $data) && $data['password'] !== "") {
            $user->password = $data["password"];
        }

        $user->role = $data['role'];

        $result = $user->save();

        if(!$result) {
            return false;
        }

        return true;
    }

    public function newpass(String $token, $user, array $data): bool
    {

        if(!filter_var($data['password'], FILTER_SANITIZE_STRING)) {
            return false;
        }

        $user->password = $data['password'];

        if(!$user->save()) {
            return false;
        }

        $tryid = (new Recovers())->find("token = :token", "token={$token}")->fetch()->id;
        $try = (new Recovers())->findById($tryid)->destroy();

        return true;

    }
}