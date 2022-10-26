<?php


namespace Source\Controllers;


use Source\Facades\MailerFacade;
use Source\Models\ContatosModel;
use Source\Models\EmpresasModel;
use Source\Models\FiscalModel;
use Source\Support\Email;

class Mailer extends Controller
{

    /** @var MailerFacade */
    private $send;

    public function __construct($router)
    {
        parent::__construct($router, "main");
    }

    public function email(array $data): void
    {

        if($_SESSION["logged"] != true) {
            header("Location:".url());
            return;
        }

        $mail = array();

        if($data['type'] !== 'Petrotrans') {

            switch ($data['type']) {
                case 'financeiroPagar':
                    $fiscal = (new FiscalModel())->find("id = :id", "id={$data['id']}")->fetch();
                    $email = $fiscal->empresas()->email;

                    $mail['email'] = $email;
                    $mail['msg'] = "Mensagem para {$fiscal->empresas()->name}";
                    $mail['assunto'] = "A Pagar";
                    $mail['anexos'] = explode(" ; ", $fiscal->comprovantes);
                    break;

                case 'financeiroReceber':
                    $fiscal = (new FiscalModel())->find("id = :id", "id={$data['id']}")->fetch();
                    $email = $fiscal->empresas()->email;

                    $mail['email'] = $email;
                    $mail['msg'] = "";
                    $mail['assunto'] = "CTe {$fiscal->cte} e boleto de transporte";
                    $mail['anexos'] = "";
                    break;
            }

        } else {
            $mail['email'] = "";
            $mail['msg'] = "";
            $mail['assunto'] = "";
            $mail['anexos'] = [];
        }

        echo $this->view->render("mailer", [
            "title" => "Enviar Email",
            "data" => $data,
            "mail" => $mail
        ]);
    }

    public function send(array $data): void
    {
        if(isset($data['emails'])) {
            $emails = explode("; ", $data['emails']);
        } else {
            $emails = [];
            $contatos = (new ContatosModel())->find("status = :status", "status={$data['contatos']}")->fetch(true);

            if($contatos) {
                foreach ($contatos as $contato) {
                    if(!empty($contato->email)){
                        foreach (explode("; ", $contato->email) as $item) {
                            array_push($emails, $item);
                        }
                    }
                }
            }
        }

        foreach ($emails as $email) {
            (new Email())->bootstrap(
                $data['assunto'],
                $email,
                $email,
                $data['corpo'],
                "massa"
            )->queue();

            //LOGGER
            (new \Source\Support\Log("enviar_email"))
                ->archive()
                ->info("Envio de e-mail realizado para: {$email}");
        }

        header("Location:".url("mailer/Petrotrans/none"));
        return;
    }
}
