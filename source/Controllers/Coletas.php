<?php


namespace Source\Controllers;


use Dompdf\Dompdf;
use Dompdf\Options;
use Example\Models\User;
use Source\Facades\ColetasFacade;
use Source\Facades\MotoristasFacade;
use Source\Facades\TransportesFacade;
use Source\Models\ColetasModel;
use Source\Models\ContatosModel;
use Source\Models\CotacoesModel;
use Source\Models\EmpresasModel;
use Source\Models\ContatosEmpresaModel;
use Source\Models\MotoristasModel;
use Source\Models\MsgWhatsappModel;
use Source\Models\TransportesModel;
use Source\Models\Users;
use Source\Support\Email;
use Source\Support\Whatsapp;

class Coletas extends Controller
{

    /** @var MotoristasFacade */
    private $newMotorista;

    /** @var ColetasFacade */
    private $new;
    private $delete;
    private $mail;
    private $edit;
    private $apresentacao;
    private $user;

    public function __construct($router)
    {
        parent::__construct($router, "main");

        if($_SESSION["logged"] == true) {
            $this->user = (new Users())->findById($_SESSION['id'])->data();
        }
    }

    public function cadastrar(array $data): void
    {

        if($_SESSION["logged"] != true) {
            header("Location:".url());
            return;
        }

        $transporte = (new TransportesModel())->find("id = :id", "id={$data['transporte']}")->fetch();
        $motoristas = (new MotoristasModel())->find()->fetch(true);
        
       $nomeempresa = $transporte->empresa_name;
      	
       $empresa = (new EmpresasModel())->find("name = :name", "name={$nomeempresa}")->fetch();
			
	    $cnpj = $empresa->cnpj;
			
	    $contatos = (new ContatosEmpresaModel())->find("cnpj = :cnpj", "cnpj={$cnpj}")->fetch(true);
	
        echo $this->view->render("criarOC", [
            "title" => "Cadastrar O.C",
            "transporte" => $transporte,
            "motoristas" => $motoristas,
            "contatos" => $contatos
        ]);

    }

    public function editarOC(array $data): void
    {

        if($_SESSION["logged"] != true) {
            header("Location:".url());
            return;
        }

        $coleta = (new ColetasModel())->find("id = :id", "id={$data['id']}")->fetch();
        $motoristas = (new MotoristasModel())->find()->fetch(true);
        $transporte = (new TransportesModel())->find("id = :id", "id={$coleta->transporte}")->fetch();
        $motorista = (new MotoristasModel())->find("name = :name", "name={$coleta->motorista}")->fetch();
        
        $name = $transporte->empresa_name;
        
        $empresa = (new EmpresasModel())->find("name = :name", "name={$name}")->fetch();
        
        $cnpj = $empresa->cnpj;
			
        $contatos = (new ContatosEmpresaModel())->find("cnpj = :cnpj", "cnpj={$cnpj}")->fetch(true);


        echo $this->view->render("editarOC", [
            "title" => "Editar O.C",
            "coleta" => $coleta,
            "motoristas" => $motoristas,
            "transporte" => $transporte,
            "motoristaColeta" => $motorista,
            "contatos" => $contatos
        ]);

    }

    public function phone(?array $data)
    {
        $coleta = (new ColetasModel())->find("id = :id and telefones IS NOT NULL", "id={$data['id']}")->fetch();

        if(!$coleta){
            echo json_encode(["msg"=> "<h4>Essa coleta não tem nenhum numero cadastrado</h4>"]);
            return;
        }

        $transportes = (new \Source\Models\TransportesModel())->find("id = :n", "n={$coleta->transporte}")->fetch()->empresa_name;
        $empresa = (new EmpresasModel())->find("name = :n", "n={$transportes}")->fetch();
        $telefones = (new ContatosModel())->find("cnpj = :cnpj", "cnpj={$empresa->cnpj}")->fetch(true);
        $html = null;
        $html .= "<a href='".url("empresa/edit/{$empresa->id}")."'>Editar Empresa</a><br><br>";
        if(!empty($telefones)){
            foreach ($telefones as $telefone) {
                $html .= "<kabel><input type='checkbox' name='telefone[]' checked value='".deixarNumero($telefone->contato)."'> {$telefone->responsavel}</kabel>&nbsp&nbsp&nbsp";
            }
        }else{
            $html .= "<label>Não foi encontrado nenhum número de telefone para essa coleta</label>";
        }
        $html .= "<input name='id_ordem' value='{$coleta->id}' type='hidden'>";

        echo json_encode(["msg"=> "{$html}"]);
    }

    public function phoneSend(array $data)
    {
        $coletaA = (new ColetasModel())->findById($data['id_ordem']);
        if(!$coletaA){
            echo json_encode(["msg"=>"ordem de coleta não existe"]);
            return;
        }

        //LOGGER
        (new \Source\Support\Log("coletas"))
            ->archive()
            ->info("Iniciado envio ordem de coleta");

        if(!empty($data['telefone'])){
            //Enviar mensagem para motorista
            if(!empty($coletaA->getMotorista()->telefone)){
                $numeroMotorista = deixarNumero(str_replace("+55", "", $coletaA->getMotorista()->telefone));

                $wppM = (new Whatsapp());
                $dadosM = "Acordo de transporte TRANSPETRO: <br>";
                $dadosM .= "MOTORISTA: ".$coletaA->motorista."  <br>";
                $dadosM .= "CPF: {$coletaA->getMotorista()->cpf} <br>";
                $dadosM .= "PLACA DO VEICULO: {$coletaA->placa_veiculo} <br>";
                $dadosM .= "PLACA DO REBOQUE: {$coletaA->placa_reboque} <br>";
                $dadosM .= "PESO DA MERCADORIA:  {$coletaA->getCotacoes()->peso}<br>";
                $dadosM .= "VALOR ACORDADO: *R$ ".str_price($coletaA->valor)."*<br>";
                $dadosM .= "DESCONTO: {$coletaA->desconto}<br>";
                $dadosM .= "MOTIVO DESCONTO: *{$coletaA->motivo_desconto}*<br>";
                $dadosM .= "FORMA DE PAGAMENTO: {$coletaA->pagamento}<br>";
                $dadosM .= "BUONNY: {$coletaA->getMotorista()->buonny}<br>";
                $dadosM .= "*ENDEREÇO DE COLETA:* {$coletaA->endereco_coleta} <br>";
                $dadosM .= "*ENDEREÇO DE ENTREGA:* {$coletaA->endereco_entrega} <br>";
                $dadosM .= "*Saldos somente mediante a envio dos comprovantes assinados via correios*<br>";
                $dadosM .= "Fones Alternativo da Petrotrans: <br>";
                $dadosM .= "41 9.9649-5078 / 41 9.9649-4569 / 41 9.9649-4624<br>";

                if($wppM->bootstrap($numeroMotorista, $dadosM)->queue()){
                    //LOGGER
                    (new \Source\Support\Log("coletas"))
                        ->archive()
                        ->info("Enviado ordem de coleta para o motorista: {$coletaA->motorista}");

                    //Pega todas as mensagens dedicadas ao motorista
                    $wppModel = (new MsgWhatsappModel())->find("type = 'motorista'")->fetch(true);
                    foreach ($wppModel as $wppItem) {
                        $wppM->bootstrap($numeroMotorista, $wppItem->content)->queue();
                    }
                }
            }

            // Enviar mensagem para clientes
            $wpp = (new Whatsapp());
            $success = [];
            $error = [];
            foreach ($data['telefone'] as $numero) {
                $dados = "ORDEM DE COLETA: ".$coletaA->coleta_id."  <br>";
                $dados .= "MOTORISTA: {$coletaA->motorista} <br>";
                $dados .= "CPF: {$coletaA->getMotorista()->cpf} <br>";
                $dados .= "PLACA DO VEICULO: {$coletaA->placa_veiculo} <br>";
                $dados .= "PLACA DO REBOQUE: {$coletaA->placa_reboque} <br>";
                $dados .= "CARROCERIA: {$coletaA->getMotorista()->carroceria} <br>";
                $dados .= "MODELO: {$coletaA->getMotorista()->modelo} <br>";
                $dados .= "PESO DA MERCADORIA:  {$coletaA->getCotacoes()->peso} kg<br>";
                $dados .= "VALOR DO FRETE: *R$ ".str_price($coletaA->getCotacoes()->valor_cotacao)."* <br>";
                $dados .= "FORMA DE PAGAMENTO: {$coletaA->getCotacoes()->pagamento}<br>";
                $dados .= "*ENDEREÇO DE COLETA:* {$coletaA->endereco_coleta} <br>";
                $dados .= "*ENDEREÇO DE ENTREGA:* {$coletaA->endereco_entrega} <br>";


                if($wpp->bootstrap($numero, $dados)->queue()){
                    //LOGGER
                    (new \Source\Support\Log("coletas"))
                        ->archive()
                        ->info("Enviado ordem de coleta para o cliente ID coleta: {$coletaA->coleta_id}");

                    $wppModel = (new MsgWhatsappModel())->find("type = 'cliente'")->fetch(true);
                    foreach ($wppModel as $wppItem) {
                        $wpp->bootstrap($numero, $wppItem->content)->queue();
                    }
                    $success[] = $numero;
                }else{
                    $error[] = $numero;
                }
            }


            //GERAR PDF
            $options = new Options();

            $options->set('isRemoteEnable', FALSE);
            $dompdf = new Dompdf($options);

            $coleta = (new ColetasModel())->find("id = :id", "id={$data['id_ordem']}")->fetch();
            $transporte = (new TransportesModel())->find("id = :id", "id={$coleta->transporte}")->fetch();
            $motorista = (new MotoristasModel())->find("name = :name", "name={$coleta->motorista}")->fetch();
            $cotacao = (new CotacoesModel())->find("id = :id", "id={$transporte->cotacao_id}")->fetch();
            $empresa = (new EmpresasModel())->find("name = :name", "name={$cotacao->cliente}")->fetch();
            $contatos = (new ContatosModel())->find("cnpj = :cnpj", "cnpj={$empresa->cnpj}")->fetch(true);

            ob_start();

            echo $this->view->render("visualizarPDF", [
                "title" => "Visualizar PDF",
                "coleta" => $coleta,
                "transporte" => $transporte,
                "motorista" => $motorista,
                "cotacao" => $cotacao,
                "empresa" => $empresa
            ]);

            $dompdf->loadHtml(ob_get_clean());

            $dompdf->setPaper("A4");

            $dompdf->render();
            file_put_contents("theme/pdf/".$transporte->empresa_name."-".$coleta->id.".pdf", $dompdf->output());
            //FIm PDF

            $path = "theme/pdf/".$transporte->empresa_name."-".$coleta->id.".pdf";
            $this->mail = new Email();

            foreach ($contatos as $contato) {
                $mail = $this->mail->bootstrap(
                    "ORDEM DE COLETA PETROTRANS – Origem: {$transporte->origem} x Destino: {$transporte->destino}.",
                    $contato->email,
                    $contato->user_name,
                    "
                    Boa tarde Srs, 
                    <br/>
                    <br/>
                    Por gentileza, liberar a coleta do motorista que deve chegar hoje ".date("d/m/Y", strtotime($transporte->data_liberacao))." referente a coleta da empresa {$transporte->empresa_name}.
                    <br/>    
                    <br/>
                        Segue dados do veículo, caso haja necessidade: <br/>
                    ·       Motorista: {$transporte->motorista_name} <br/>
                    ·       CPF: {$motorista->cpf} <br/>
                    ·       Carroceria: {$motorista->carroceria}. <br/>
                    ·       Placa veiculo: {$motorista->placa_veiculo} <br/>
                    ·       Placa reboque: {$motorista->placa_reboque} <br/>
                    ·       Modelo/Ano: {$motorista->modelo} <br/>
                        <br/>
                    <br/>
                    <br/>
                    <img src='https://www.petrotrans.com.br/theme/images/assinatura.png' alt='Assinatura'>
                ",
                    "coleta"
                )->addBcc(
                    "cotacoes@petrotrans.com.br",
                    "Contações"
                )->addBcc(
                    "victor.fiscal@petrotrans.com.br",
                    "Victor"
                )->addBcc(
                    "petrotrans.transpetro@gmail.com",
                    "Petrotrans"
                );

                //LOGGER
                (new \Source\Support\Log("coletas"))
                    ->archive()
                    ->info("Enviado E-mail ordem de coleta para o e-mail: {$contato->email}");

                if(!$mail->queue()) {
                    echo json_encode("Não foi possível enviar o email.");
                    return;
                }
            }

            if(!empty($error)){
                echo json_encode(["msg"=>"Erro ao enviar mensagem para o número: ".implode(", ", $error)]);
                return;
            }else{
                echo json_encode(["msg"=>"Todas as mensagem enviada com sucesso"]);
                return;
            }
        }else{
            echo json_encode(["msg"=>"Escolha pelo menos um numero de telefone"]);
            return;
        }
    }

    public function visualizar(array $data): void
    {
        $options = new Options();

        $options->set('isRemoteEnable', FALSE);
        $dompdf = new Dompdf($options);

        $coleta = (new ColetasModel())->find("id = :id", "id={$data['id']}")->fetch();
        $transporte = (new TransportesModel())->find("id = :id", "id={$coleta->transporte}")->fetch();
        $motorista = (new MotoristasModel())->find("name = :name", "name={$coleta->motorista}")->fetch();
        $cotacao = (new CotacoesModel())->find("id = :id", "id={$transporte->cotacao_id}")->fetch();
        $empresa = (new EmpresasModel())->find("name = :name", "name={$cotacao->cliente}")->fetch();

        ob_start();

        echo $this->view->render("visualizarPDF", [
            "title" => "Visualizar PDF",
            "coleta" => $coleta,
            "transporte" => $transporte,
            "motorista" => $motorista,
            "cotacao" => $cotacao,
            "empresa" => $empresa
        ]);

        $dompdf->loadHtml(ob_get_clean());

        $dompdf->setPaper("A4");

        $dompdf->render();
        $dompdf->stream("ordem-de-coleta.pdf", ["Attachment" => false]);

    }

    public function new(array $data): void
    {
        if($data['motorista'] === "false") {

            $this->newMotorista = new MotoristasFacade();
            $this->new = new ColetasFacade();
            $this->edit = new TransportesFacade();

            if(!$this->newMotorista->new($data) || !$this->new->new($data)) {
                header("Location:".url("main/coletas"));
                return;
            }

            //LOGGER
            (new \Source\Support\Log("coletas"))
                ->archive()
                ->info("Nova coleta: adm = false");

            header("Location:".url("main/coletas"));
            return;

        } else if($data['motorista'] === "true") {
            $this->new = new ColetasFacade();

            if(!$this->new->new($data)) {
                header("Location:".url("main/coletas"));
                return;
            }
            //LOGGER
            (new \Source\Support\Log("coletas"))
                ->archive()
                ->info("Nova coleta: adm = true");

            header("Location:".url("main/coletas"));
            return;
        }
    }

    public function edit(array $data): void
    {

        $this->edit = new ColetasFacade();

        if(!$this->edit->edit($data)) {

            header("Refresh:0");
            return;
        }

        //LOGGER
        (new \Source\Support\Log("coleta"))
            ->archive()
            ->info("Coleta editado");

        header("Location:".url("main/coletas"));
        return;
    }

    public function download(array $data): void
    {
        $options = new Options();

        $options->set('isRemoteEnable', FALSE);
        $dompdf = new Dompdf($options);

        $coleta = (new ColetasModel())->find("id = :id", "id={$data['id']}")->fetch();
        $transporte = (new TransportesModel())->find("id = :id", "id={$coleta->transporte}")->fetch();
        $motorista = (new MotoristasModel())->find("name = :name", "name={$coleta->motorista}")->fetch();
        $cotacao = (new CotacoesModel())->find("id = :id", "id={$transporte->cotacao_id}")->fetch();
        $empresa = (new EmpresasModel())->find("name = :name", "name={$cotacao->cliente}")->fetch();

        ob_start();

        echo $this->view->render("visualizarPDF", [
            "title" => "Visualizar PDF",
            "coleta" => $coleta,
            "transporte" => $transporte,
            "motorista" => $motorista,
            "cotacao" => $cotacao,
            "empresa" => $empresa
        ]);

        $dompdf->loadHtml(ob_get_clean());

        $dompdf->setPaper("A4");

        $dompdf->render();
        $dompdf->stream("ordem-de-coleta-{$coleta->id}.pdf");
    }

    public function mail(array $data): void
    {
        if(isset($_GET['emailapresentacao'])){

            $options = new Options();

            $options->set('isRemoteEnable', FALSE);
            $dompdf = new Dompdf($options);

            $data['msg'] = "<br><center>Teste</center>";
            $data['emailapresentacao'] = $_GET['emailapresentacao'];
            $data['nomecontato'] = $_GET['nomecontato'];

            $dompdf->loadHtml(ob_get_clean());

            $dompdf->setPaper("A4");

            $dompdf->render();
            file_put_contents("theme/pdf/apresentacao.pdf", $dompdf->output());

            $ano = date('Y');
            $mes = date('m');
            $path = "theme/pdf/apresentacao/pdfapresentacao/".$ano."/".$mes."/apresentacao.pdf";
            $this->mail = new ColetasFacade();

            if(!$this->mail->mail($data, $path)) {
                echo json_encode("Não foi possível enviar o email.");
                return;
            }

            //LOGGER
            (new \Source\Support\Log("coletas"))
                ->archive()
                ->info("Email Enviado.");

            echo json_encode("Email Enviado.");

        }
        else{
            $options = new Options();

            $options->set('isRemoteEnable', FALSE);
            $dompdf = new Dompdf($options);

            $coleta = (new ColetasModel())->find("id = :id", "id={$data['id']}")->fetch();
            $transporte = (new TransportesModel())->find("id = :id", "id={$coleta->transporte}")->fetch();
            $motorista = (new MotoristasModel())->find("name = :name", "name={$coleta->motorista}")->fetch();
            $cotacao = (new CotacoesModel())->find("id = :id", "id={$transporte->cotacao_id}")->fetch();
            $empresa = (new EmpresasModel())->find("name = :name", "name={$cotacao->cliente}")->fetch();

            ob_start();

            echo $this->view->render("visualizarPDF", [
                "title" => "Visualizar PDF",
                "coleta" => $coleta,
                "transporte" => $transporte,
                "motorista" => $motorista,
                "cotacao" => $cotacao,
                "empresa" => $empresa
            ]);

            $dompdf->loadHtml(ob_get_clean());

            $dompdf->setPaper("A4");

            $dompdf->render();
            file_put_contents("theme/pdf/".$transporte->empresa_name."-".$coleta->id.".pdf", $dompdf->output());

            $path = "theme/pdf/".$transporte->empresa_name."-".$coleta->id.".pdf";
            $this->mail = new ColetasFacade();

            if(!$this->mail->mail($data, $path)) {
                echo json_encode("Não foi possível enviar o email.");
                return;
            }

            //LOGGER
            (new \Source\Support\Log("coleta"))
                ->archive()
                ->info("Email Enviado.");

            echo json_encode("Email Enviado.");
        }
        
    }

    public function message(array $data): void
    {

        $coleta = (new ColetasModel())->find("id = :id", "id={$data['id']}")->fetch();
        $motorista = (new MotoristasModel())->find("name = :motorista", "motorista={$coleta->motorista}")->fetch();
        $transporte = (new TransportesModel())->find("id = :id", "id={$coleta->transporte}")->fetch();

        $mensagem = "
                Boa tarde Srs, 
                <br/>
                <br/>
                Por gentileza, liberar a coleta do motorista que deve chegar hoje {$coleta->date} referente a coleta da empresa {$transporte->empresa_name}.
                <br/>    
                <br/>
                    Segue dados do veículo, caso haja necessidade: <br/>
                ·       Motorista: {$transporte->motorista_name} <br/>
                ·       CPF: {$motorista->cpf} <br/>
                ·       Carroceria: {$motorista->carroceria}. <br/>
                ·       Placa veiculo: {$motorista->placa_veiculo} <br/>
                ·       Placa reboque: {$motorista->placa_reboque} <br/>
                ·       Modelo/Ano:
            ";

            $telefones = explode("; ", $coleta->telefones);

        foreach ($telefones as $telefone) {
            $msg = new Whatsapp();
            $msg->bootstrap($telefone, $mensagem)->sendQueue();

            //LOGGER
            (new \Source\Support\Log("coleta"))
                ->archive()
                ->info("Whatsapp enviado: telefone: {$telefone}");

        }
    }

    public function delete(array $data): void
    {

        $this->delete = new ColetasFacade();

        if(!$this->delete->delete($data)) {
            echo json_encode("Não foi possível deletar a O.C.");
            return;
        }

        //LOGGER
        (new \Source\Support\Log("coleta"))
            ->archive()
            ->info("O.C. deletada. ID: {$data['id']}");

        echo json_encode("O.C. deletada.");
    }

}
