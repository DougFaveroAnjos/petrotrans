<?php


namespace Source\Controllers;


use Source\Facades\ContatoFacade;
use Source\Facades\ColetasFacade;
use Source\Models\ContatosModel;
use Source\Models\EmpresasModel;
use Source\Models\ColetasModel;
use Source\Models\Users;

class Contato extends Controller
{

    /** @var ContatoFacade */
    private $new;
    private $delete;
    private $edit;
    private $mail;
    private $apresentacao;

    public function __construct($router)
    {
        parent::__construct($router, "main");
    }

    public function criarContato(): void
    {

        if($_SESSION["logged"] != true) {
            header("Location:".url());
            return;
        }

        echo $this->view->render("criarContato", [
            "title" => "Criar Contato",
        ]);
    }

    public function editContato(array $data): void
    {
        if($_SESSION["logged"] != true) {
            header("Location:".url());
            return;
        }

        $contato = (new ContatosModel())->find("id = :id", "id={$data['id']}")->fetch();

        echo $this->view->render("editContato", [
            "title" => "Editar Contato",
            "contato" => $contato
        ]);
    }

    public function new(array $data): void
    {

        $this->new = new ContatoFacade();

        if(!$this->new->new($data)) {
            echo $this->ajaxMessage("Ocorreu um Erro ao Criar Contato", "error");
            return;
        }

        //LOGGER
        (new \Source\Support\Log("contato"))
            ->archive()
            ->info("Contato Criado");

//        $this->delete->delete($data);
        echo "<script>alert('Contato Criado!')</script>";
        //echo $this->ajaxMessage("Contato Criado!", "success");

    }

    public function delete(array $data): void
    {
        $this->delete = new ContatoFacade();

        if(!$this->delete->delete($data)) {
            echo json_encode("Não foi possível deletar contato.");
            return;
        }

        //LOGGER
        (new \Source\Support\Log("contato"))
            ->archive()
            ->info("Contato Deletado: ID: {$data['id']}");

        echo json_encode("Contato Deletado.");
    }

    public function edit(array $data): void
    {
   
        $this->edit = new ContatoFacade();

        if(!$this->edit->edit($data)) {
            echo json_encode("Não foi possível editar contato.");
            return;
        }

        //LOGGER
        (new \Source\Support\Log("contato"))
            ->archive()
            ->info("Contato Editado");

        echo json_encode("Contato Editado.");
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

    public function mail(array $data): void
    {
   
        $options = new Options();

        $options->set('isRemoteEnable', FALSE);
        $dompdf = new Dompdf($options);

        $coleta = (new ColetasModel())->find("id = :id", "id={$data['id']}")->fetch();
        $transporte = (new TransportesModel())->find("id = :id", "id={$coleta->transporte}")->fetch();
        $motorista = (new MotoristasModel())->find("name = :name", "name={$coleta->motorista}")->fetch();
        $cotacao = (new CotacoesModel())->find("id = :id", "id={$transporte->cotacao_id}")->fetch();
        $empresa = (new EmpresasModel())->find("name = :name", "name={$cotacao->cliente}")->fetch();
        $user = (new Users())->find("id = :id", "id={$_SESSION['id']}")->fetch();

        ob_start();

        echo $this->view->render("visualizarPDF", [
            "title" => "Visualizar PDF",
            "coleta" => $coleta,
            "transporte" => $transporte,
            "motorista" => $motorista,
            "cotacao" => $cotacao,
            "empresa" => $empresa,
            'user' => $user
        ]);

        $dompdf->loadHtml(ob_get_clean());

        $dompdf->setPaper("A4");

        $dompdf->render();
        file_put_contents("theme/pdf/".$transporte->empresa_name."-".$coleta->id.".pdf", $dompdf->output());

        $path = "theme/pdf/apresentacao_petrotrans_transportes_e_logistica.pdf";
        $this->mail = new ColetasFacade();

        if(!$this->mail->mail($data, $path)) {
            echo json_encode("Não foi possível enviar o email.");
            return;
        }

        //LOGGER
        (new \Source\Support\Log("contato"))
            ->archive()
            ->info("Email Enviado");

        echo json_encode("Email Enviado.");
    }

}
