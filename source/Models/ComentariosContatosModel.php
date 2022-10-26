<?php


namespace Source\Models;


use CoffeeCode\DataLayer\DataLayer;

class ComentariosContatosModel extends DataLayer
{

    public function __construct()
    {
        parent::__construct("comentarios_contatos", ["contato_id", "comentario"]);
    }

}