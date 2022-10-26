<?php


namespace Source\Controllers;


use Source\Facades\FinanceiroFacade;
use Source\Facades\FiscalFacade;
use Source\Models\ContatosModel;
use Source\Models\FiscalModel;

class Financeiro extends Controller
{

    /** @var FinanceiroFacade */
    private $add;
    private $delete;

    public function __construct($router)
    {
        parent::__construct($router, "main");
    }

    public function emailSend(?array $data)
    {
        if(empty($data['email']) || !is_array($data['email'])){
            echo json_encode(["msg"=>"Selecione pelo menos um e-mail para prosseguir"]);
            return;
        }

        $fiscal = (new FiscalModel())->findTransportes("f.id = :id", "id={$data['id_fiscal']}")->fetch();

        if(!$fiscal){
            echo json_encode(["msg"=>"O Código do fiscal informado não existe cadastrado"]);
            return;
        }


        foreach ($data['email'] as $email) {
            //Verifica a existência do contato
            $contato = (new ContatosModel())->find("email = :email", "email={$email}")->fetch();

            if(!$contato){
                continue;
            }

            $sendMail = (new \Source\Support\Email());
            $sendMail->bootstrap(
                "CTe {$fiscal->cte} e boleto referente ao transporte",
                $email,
                $contato->responsavel,
                "financeiro"
            )->view(
                "email",
                [
                    "subject" => "CTe {$fiscal->cte} e boleto referente ao transporte",
                    "cte" => $fiscal->cte,
                    "responsavel" => $contato->responsavel,
                    "status" => explode(";", $fiscal->status)[0],
                    "empresa" => $fiscal->empresas()->name,
                    "valor" => "R$ ".str_price($fiscal->cotacoes()->valor_cotacao),
                    "origem" => $fiscal->origem,
                    "destino" => $fiscal->destino
                ]
            );

            if($fiscal->pdf_cte){
                $sendMail->attach(str_replace("/theme", "theme",$fiscal->pdf_cte), "cte_pdf.pdf");
            }

            if($fiscal->boletos){
                $boletos = explode(";", $fiscal->boletos);
                if(!$boletos){
                    $boletos = explode(",", $fiscal->boletos);
                }

                $c = 0;
                foreach ($boletos as $boleto) {
                    $c++;
                    $sendMail->attach(str_replace("/theme", "theme",$boleto), "boleto_{$c}.PDF");
                }
            }

            $sendMail->queue();

            //LOGGER
            (new \Source\Support\Log("financeiro"))
                ->archive()
                ->info("E-mail enviado com sucesso! email={$email}");

        }

        echo json_encode(['msg' => "E-mail(s) enviados com sucesso"]);
        return;
    }

    public function addC(array $data): void
    {
        if($_SESSION["logged"] != true) {
            header("Location:".url());
            return;
        }

        $fiscal = (new FiscalModel())->find("id = :id", "id={$data['id']}")->fetch();

        echo $this->view->render("addC", [
            "title" => "Adicionar Comprovantes",
            "fiscal" => $fiscal,
            "comprovantes" => explode(" ; ", $fiscal->comprovantes),
            "valores" => explode(" ; ", $fiscal->comprovantes_valores),
            "historico" => explode(" ; ", $fiscal->comprovantes_historico)
        ]);

    }

    public function addB(array $data): void
    {
        if($_SESSION["logged"] != true) {
            header("Location:".url());
            return;
        }

        $fiscal = (new FiscalModel())->find("id = :id", "id={$data['id']}")->fetch();

        echo $this->view->render("addB", [
            "title" => "Adicionar Boletos",
            "fiscal" => $fiscal,
            "boletos" => explode(" ; ", $fiscal->boletos),
            "valores" => explode(" ; ", $fiscal->boletos_valores),
            "historico" => explode(" ; ", $fiscal->boletos_historico),
            "datas" => explode(" ; ", $fiscal->boletos_datas)
        ]);
    }

    public function add(array $data): void
    {

        $this->add = new FinanceiroFacade();

       if(!$this->add->add($data)) {
            header("Location:".url("main/financeiroPagar"));
            return;
        }

        //LOGGER
        (new \Source\Support\Log("financeiro"))
            ->archive()
            ->info("Comprovante Indexado");

        header("Location:".url("main/financeiroPagar"));
    }

    public function addBol(array $data): void
    {

        $this->add = new FinanceiroFacade();

        if(!$this->add->addBol($data)) {
            header("Location:".url("main/financeiroReceber"));
            return;
        }

        //LOGGER
        (new \Source\Support\Log("financeiro"))
            ->archive()
            ->info("Boleto adicionado");

        header("Location:".url("main/financeiroReceber"));
    }

    public function delete(array $data): void
    {

        $this->delete = new FinanceiroFacade();

        if(!$this->delete->delete($data)) {
            echo json_encode("Não foi possível deletar os comprovantes.");
            return;
        }

        //LOGGER
        (new \Source\Support\Log("financeiro"))
            ->archive()
            ->info("Comprovante deletado! ID:{$data["id"]}");

        echo json_encode("Comprovantes Deletados.");
    }

    public function deleteBol(array $data): void
    {

        $this->delete = new FinanceiroFacade();

        if(!$this->delete->deleteBol($data)) {
            echo json_encode("Não foi possível deletar os boletos.");
            return;
        }

        //LOGGER
        (new \Source\Support\Log("financeiro"))
            ->archive()
            ->info("Boletos deletado! ID:{$data["id"]}");

        echo json_encode("Boletos Deletados.");

    }
}