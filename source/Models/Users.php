<?php


namespace Source\Models;


use CoffeeCode\DataLayer\DataLayer;
use Exception;

class Users extends DataLayer
{
public function __construct()
{
    parent::__construct("users", ["first_name", "last_name", "email", "password"]);
}

    /**
     * @return bool
     */
public function save(): bool
{
    if (
        !$this->validatePassword() ||
        !parent::save()
    ) {
        return false;
    }

    return true;
}

    /**
     * @return bool
     */
public function validatePassword(): bool
{
   if (empty($this->password) || strlen($this->password) < 5) {
    $this->fail = new Exception("Informe uma senha com pelo menos 5 caracteres");
    return false;
   }

   if (password_get_info($this->password)['algo']) {
       return true;
   }

   $this->password = password_hash($this->password, PASSWORD_DEFAULT);
   return true;
}
}