<?php


namespace Source\Controllers;


use DateTime;
use Source\Facades\ComentariosContatosFacade;
use Source\Models\ComentariosContatosModel;

class ComentariosContatos extends Controller
{

    /** @var ComentariosContatosFacade */
    private $new;

 public function __construct($router)
 {
     parent::__construct($router);
 }

 public function render(array $data): void
 {

     $comentarios = (new ComentariosContatosModel())->find("contato_id = :id", "id={$data['id']}")->fetch(true);
     $result = "";

     if(!$comentarios) {
         echo json_encode("Nenhum Comentario Encontrado.");
         return;
     }

     foreach ($comentarios as $comentario) {
         $result .= '<div class="comentario '.$comentario->id.'" style="white-space: normal; border-bottom: 1px solid #f96332; padding: .2rem; margin: .2rem; word-wrap: break-word">
                        <h6>'.$comentario->nome.'</h6>
                        <span style="opacity: .7; font-size: .7rem">'.(new DateTime($comentario->created_at))->format('d/m/Y H:i:s').'</span>
                        <p style="margin-bottom: .4rem; opacity: .9; font-size: .8rem; word-wrap: break-word">'.$comentario->comentario.'</p>
                    </div>';
     }

     echo json_encode($result);
 }

 public function new(array $data): void
 {

     $this->new = new ComentariosContatosFacade();

     if(!$this->new->new($data)) {
         echo json_encode("Ocorreu um Erro.");
         return;
     }

     //LOGGER
     (new \Source\Support\Log("contato"))
         ->archive()
         ->info("Comentario Adicionado.");

     echo json_encode("Comentario Adicionado.");
 }
}