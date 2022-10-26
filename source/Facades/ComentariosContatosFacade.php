<?php


namespace Source\Facades;


use Source\Models\ComentariosContatosModel;

class ComentariosContatosFacade
{

    public function new(array $data): bool {

        $comentario = new ComentariosContatosModel();

        $comentario->contato_id = $data['contato_id'];
        $comentario->nome = $_SESSION['name'];
        $comentario->comentario = $data['comentario'];

        if(!$comentario->save()) {
            return false;
        }

        return true;
    }

}