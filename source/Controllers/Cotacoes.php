<?php


namespace Source\Controllers;

use Source\Facades\CotacoesFacade;
use Source\Models\CotacoesModel;
use Source\Models\EmpresasModel;
use Source\Models\ContatosModel;
use Source\Models\Users;
use Source\Support\Email;
use Source\Support\Whatsapp;

class Cotacoes extends Controller
{

    /** @var CotacoesFacade */
    private $new;
    private $delete;
    private $duplicate;
    private $update;
    private $mail;

    public function __construct($router)
    {
        parent::__construct($router, "main");
    }

    public function criarCotacao(array $data): void
    {
        if($_SESSION["logged"] != true) {
            header("Location:".url());
            return;
        }

        $empresas = (new EmpresasModel())->find()->fetch(true);
        $nameEmpresas = array();
        $users = (new Users())->find()->fetch(true);

        if($empresas){
            foreach ($empresas as $key => $empresa) {
                $nameEmpresas[$key] = $empresa->name;
            }
        }

        echo $this->view->render("novaCotacao", [
            "title" => "Nova Cotação",
            "empresas" => $nameEmpresas,
            "users" => $users,
            "userE" => (new Users())->findById($_SESSION['id'])
        ]);
    }

    public function editCotacao(array $data): void
    {
        if($_SESSION["logged"] != true) {
            header("Location:".url());
            return;
        }

        $empresas = (new EmpresasModel())->find()->fetch(true);
        $nameEmpresas = array();
        $cotacao = (new CotacoesModel())->find("id = :id", "id={$data['id']}")->fetch();
        $anexos = explode(" ; ", $cotacao->anexos);
        $users = (new Users())->find()->fetch(true);

        if($empresas){
            foreach ($empresas as $key => $empresa) {
                $nameEmpresas[$key] = $empresa->name;
            }
        }
        
        $name = $cotacao->cliente;
        	
        $contatos = (new ContatosModel())->find("empresa = :empresa", "empresa={$name}")->fetch(true);

        echo $this->view->render("editCotacao", [
            "title" => "Editar Cotação",
            "empresas" => $nameEmpresas,
            "cotacao" => $cotacao,
            "users" => $users,
            "anexos" => $anexos,
            "contatos" => $contatos,
            "empresa" => $empresa
        ]);
    }

    public function visualizar(array $data): void
    {

        $cotacao = (new CotacoesModel())->find("id = :id", "id={$data['id']}")->fetch();
        $empresa = (new EmpresasModel())->find("id = :id", "id={$cotacao->cliente_id}")->fetch();
        $petrotrans = (new EmpresasModel())->find("name = :name", "name=PETROTRANS")->fetch();

        $name = $empresa->name;

        $contatos = (new ContatosModel())->find("empresa = :empresa", "empresa={$name}")->fetch(true);


        echo $this->view->render("cotacao", [
            "title" => "Visualizar Cotação",
            "cotacao" => $cotacao,
            "empresa" => $empresa,
            "petrotrans"=> $petrotrans,
            "contatos" => $contatos
        ]);

    }

    public function analisar(array $data): void
    {

        if(!array_key_exists("km", $data) && $data['km'] !== "") {
            echo "<tr>Nenhum Resultado</tr>";
        } else {

            if(!array_key_exists("raio", $data) || $data['raio'] === "") {
                $minv = intval($data["km"]) - 200;
                $maxv = intval($data["km"]) + 200;
            } else {
                $minv = intval($data["km"]) - intval($data['raio']);
                $maxv = intval($data["km"]) + intval($data['raio']);
            }

            if($data['status'] !== "todos" && $data['status'] !== "") {
                $cotacoes = (new CotacoesModel())->find("km >= :kmmin AND km <= :kmmax AND tipo_carga = :tipo AND status = :status", "kmmin={$minv}&kmmax={$maxv}&tipo={$data['tipo']}&status={$data['status']}")->order("field(status, 'Fechado', 'Não Fechou', 'Aguardando Cliente', 'Cancelado')")->fetch(true);
            } else {
                $cotacoes = (new CotacoesModel())->find("km >= :kmmin AND km <= :kmmax AND tipo_carga = :tipo", "kmmin={$minv}&kmmax={$maxv}&tipo={$data['tipo']}")->order("field(status, 'Fechado', 'Não Fechou', 'Aguardando Cliente', 'Cancelado')")->fetch(true);
            }

            $result = "";

            if(!$cotacoes) {
                $result .= "<tr> Nenhum Resultado </tr>";
            } else {
                foreach ($cotacoes as $cotacao) {

                    switch ($cotacao->status) {
                        case "Fechado":
                            $color = "rgb(30, 144, 255)";
                            break;

                        case "Aguardando Cliente":
                            $color = "yellow";
                            break;

                        case "Não Fechou":
                            $color = "red";
                            break;

                        case "Cancelado":
                            $color = "orange";
                            break;
                    }

                    $result .= "
            <tr>
                <td>".$cotacao->id."</td>
                <td style='background-color:". $color ." '>".$cotacao->status."</td>
                <td> R$ ".$cotacao->valor_cotacao."</td>
                <td>".$cotacao->tipo_carga."</td>
                <td>".$cotacao->km."</td>
                <td>".$cotacao->veiculo."</td>
                <td>".$cotacao->uf_origem."/".$cotacao->cidade_origem." x ".$cotacao->uf_destino."/".$cotacao->cidade_destino."</td>
                <td>".$cotacao->peso."</td>
                <td>".$cotacao->observacao."</td>
            </tr>
            ";
                }
            }

            echo $result;
        }

    }

    public function new(array $data): void
    {

        $cotacao = new CotacoesModel();
        $this->new = new CotacoesFacade();
        
        $data['vendedor'] = $_SESSION['name'];

        if(!$this->new->new($cotacao, $data)) {
            header("Location:".url("main/cotacoes"));
            return;
        }

        //LOGGER
        (new \Source\Support\Log("cotacao"))
            ->archive()
            ->info("Cotação cadastrada com sucesso!");

        header("Location:".url("main/cotacoes"));
        return;

    }

    public function delete(array $data): void
    {
        $cotacao = (new CotacoesModel())->find("id = :id", "id={$data['id']}")->fetch();

        $this->delete = new CotacoesFacade();

        if(!$this->delete->delete($cotacao)) {
            $_SESSION['message'] = "<div class='message error'>Não Foi Possível Excluir a Cotação</div>";
            echo $_SESSION['message'];
            return;
        }

        //LOGGER
        (new \Source\Support\Log("cotacao"))
            ->archive()
            ->info("Cotação excluída com sucesso! ID: {$data["id"]}");

        $_SESSION['message'] = "<div class='message success'>Cotação excluída com sucesso</div>";
        echo $_SESSION['message'];
    }

    public function duplicate(array $data): void
    {

        $cotacao = (new CotacoesModel())->find("id = :id", "id={$data['id']}")->fetch();
        $this->duplicate = new CotacoesFacade();

        if(!$this->duplicate->duplicate($cotacao)) {
            $_SESSION['message'] = "<div class='message error'>Não Foi Possível Duplicar a Cotação</div>";
            echo $_SESSION['message'];
            return;
        }

        //LOGGER
        (new \Source\Support\Log("cotacao"))
            ->archive()
            ->info("Cotação duplicada com sucesso");

        $_SESSION['message'] = "<div class='message success'>Cotação duplicada com sucesso</div>";
        echo $_SESSION['message'];
    }

    public function update(array $data): void
    {

        $cotacao = (new CotacoesModel())->findById($data['cotacao']);
        $this->update = new CotacoesFacade();

        if(!$this->update->update($cotacao, $data)) {

            if(!array_key_exists('edit_cotacao', $data)) {
                echo $this->ajaxMessage("Algo deu Errado", "error");
                return;
            } else {
                header("Location:".url("main/cotacoes"));
                return;
            }
        }

        if(!array_key_exists('edit_cotacao', $data)) {

            //LOGGER
            (new \Source\Support\Log("cotacao"))
                ->archive()
                ->info("Cotação Atualizada!");

            echo $this->ajaxMessage("Cotação Atualizada!", "success");
            return;
        } else {
            header("Location:".url("main/cotacoes"));
            return;
        }
    }

    // public function mailbackup(array $data): void
    // {

    //     $this->mail = new CotacoesFacade();

    //     if(!$this->mail->mail($data)) {
    //         echo json_encode("Ocorreu um Erro" );
    //         return;
    //     }

    //     echo json_encode("Cotação Enviada!");
    // }
    
    public function mail(array $data): void
    {
        //Enviar email quando clicar em tudo
        if(!empty($data['all']) && $data['all'] == "true"){
            $cotacao = (new CotacoesModel())->find("id = :id", "id={$data['id']}")->fetch();
            $empresa = (new EmpresasModel())->find("id = :id", "id={$cotacao->cliente_id}")->fetch();
            $contatos = (new ContatosModel())->find("empresa = :e", "e={$empresa->name}")->fetch(true);
            $petrotrans = (new EmpresasModel())->find("name = :name", "name=PETROTRANS")->fetch();

            $cotacao->enviado = 1;
            $cotacao->save();

            $success = [];
            $error = [];

            foreach ($contatos as $contato) {
                // Enviar E-mail
                if(!empty($contato->email)){
                    $data['emailunico'] = $contato->email;
                    $data['cliente_fob'] = $cotacao->cliente_fob;
                    $data['name'] = $empresa->name;

                    $data['destino'] = "{$cotacao->cidade_origem}/{$cotacao->uf_origem} X {$cotacao->cidade_destino} / {$cotacao->uf_destino}";
                    $data['price'] = "R$ ".str_price($cotacao->valor_cotacao);

                    $data['msg'] = $this->view->render("cotacao", [
                        "title" => "Visualizar Cotação",
                        "cotacao" => $cotacao,
                        "empresa" => $empresa,
                        "petrotrans"=> $petrotrans
                    ]);

                    if(!(new Email())->bootstrap(
                        '[PETROTRANS] - COTACAO '.$data['id'].' - '. $data['price'] .' x '.$data['destino'],
                        $contato->email,
                        $contato->user_name,
                        $data['msg'],
                        "cotacao"
                    )->queue()) {
                        echo json_encode("Ocorreu um Erro" );
                        return;
                    }

                    //LOGGER
                    (new \Source\Support\Log("cotacao"))
                        ->archive()
                        ->info("E-mail enviado com sucesso! Email: {$contato->email}");
                }

                //Enviar Whatsapp
                if(!empty($contato->contato)){
                    $wpp = (new Whatsapp());
                    $numero = deixarNumero($contato->contato);

                    $dados = "Cotação: ".$cotacao->id."  <br>";
                    $dados .= "Origem: {$cotacao->cidade_origem}/{$cotacao->uf_origem}<br>";
                    $dados .= "Destino: {$cotacao->cidade_destino} / {$cotacao->uf_destino} <br>";
                    $dados .= "Veiculo: {$cotacao->veiculo} <br>";
                    $dados .= "Volume: {$cotacao->volumes} <br>";
                    $dados .= "Peso: {$cotacao->peso} <br>";
                    $dados .= "Tipo da carga: {$cotacao->tipo_carga} <br>";
                    $dados .= "Data da coleta: {$cotacao->data_coleta} <br>";
                    $dados .= "Previsão: {$cotacao->prazo_previsao} <br>";
                    $dados .= "Valor do frete: *R$ ".str_price($cotacao->valor_cotacao)."* <br>";

                    if($wpp->bootstrap($numero, $dados)->queue()){

                        //LOGGER
                        (new \Source\Support\Log("cotacao"))
                            ->archive()
                            ->info("Whatsapp enviado com sucesso! numero: {$numero}");
                        $success[] = $numero;
                    }else{
                        $error[] = $numero;
                    }
                }

                //Envia para os administradores
                $admin = (new Users())->find("empresa IS NULL")->fetch(true);
                if($admin){
                    foreach ($admin as $item) {
                        if(empty($item->number)){
                            continue;
                        }

                        (new Whatsapp())->bootstrap(
                            $item->number,
                            $dados
                        )->queue();

                        //LOGGER
                        (new \Source\Support\Log("cotacao"))
                            ->archive()
                            ->info("Whatsapp enviado com sucesso para os administradores! Número: {$item->number}");
                    }
                }
            }

            if(!empty($error)){
                echo json_encode("Erro ao enviar mensagem para o número: ".implode(", ", $error));
                return;
            }else{
                echo json_encode("Todas as mensagem enviada com sucesso");
                return;
            }
        }else{

            //enviar email para um usuário único
            if(isset($_GET['email'])){
                $data['emailunico'] = $_GET['email'];
                $data['cliente_fob'] = $_GET['name'];
                $data['name'] = $_GET['name'];


                $cotacao = (new CotacoesModel())->find("id = :id", "id={$data['id']}")->fetch();
                $empresa = (new EmpresasModel())->find("id = :id", "id={$cotacao->cliente_id}")->fetch();
                $petrotrans = (new EmpresasModel())->find("name = :name", "name=PETROTRANS")->fetch();
                $contato = (new ContatosModel())->find("email = :e AND cnpj = :c", "e={$_GET['email']}&c={$empresa->cnpj}")->fetch();

                $cotacao->enviado = 1;
                $cotacao->save();
                $data['msg'] = $this->view->render("cotacao", [
                    "title" => "Visualizar Cotação",
                    "cotacao" => $cotacao,
                    "empresa" => $empresa,
                    "petrotrans"=> $petrotrans
                ]);

                //Enviar Whatsapp
                if(!empty($contato->contato)){

                    $wpp = (new Whatsapp());
                    $numero = deixarNumero($contato->contato);

                    $dados = "Cotação: ".$cotacao->id."  <br>";
                    $dados .= "Origem: {$cotacao->cidade_origem}/{$cotacao->uf_origem}<br>";
                    $dados .= "Destino: {$cotacao->cidade_destino} / {$cotacao->uf_destino} <br>";
                    $dados .= "Veiculo: {$cotacao->veiculo} <br>";
                    $dados .= "Volume: {$cotacao->volumes} <br>";
                    $dados .= "Peso: {$cotacao->peso} <br>";
                    $dados .= "Tipo da carga: {$cotacao->tipo_carga} <br>";
                    $dados .= "Data da coleta: {$cotacao->data_coleta} <br>";
                    $dados .= "Previsão: {$cotacao->prazo_previsao} <br>";
                    $dados .= "Valor do frete: *R$ ".str_price($cotacao->valor_cotacao)."* <br>";

                    $wpp->bootstrap($numero, $dados)->queue();

                    //LOGGER
                    (new \Source\Support\Log("cotacao"))
                        ->archive()
                        ->info("Whatsapp enviado com sucesso! numero: {$numero}");

                    //Envia para os administradores
                    $admin = (new Users())->find("empresa IS NULL")->fetch(true);
                    if($admin){
                        foreach ($admin as $item) {
                            if(empty($item->number)){
                                continue;
                            }

                            (new Whatsapp())->bootstrap(
                                $item->number,
                                $dados
                            )->queue();

                            //LOGGER
                            (new \Source\Support\Log("cotacao"))
                                ->archive()
                                ->info("Whatsapp enviado com sucesso para os administradores! numero: {$item->number}");
                        }
                    }
                }

                (new Email())->bootstrap(
                    '[PETROTRANS] - COTACAO '.$cotacao->id.' - R$ '. str_price($cotacao->valor_cotacao).' x '.$cotacao->cidade_destino."/". $cotacao->uf_destino,
                    $_GET['email'],
                    $_GET['email'],
                    $data['msg'],
                    "cotacao"
                )->queue();
            }
            else{
                if(isset($_GET['name'])){
                    $data['cliente'] = $_GET['name'];
                }
            }

            //LOGGER
            (new \Source\Support\Log("cotacao"))
                ->archive()
                ->info("Cotação Enviada! Email: {$_GET['email']}");

            echo json_encode("Cotação Enviada!");
        }
    }

    public function apresentacao(array $data): void
    {
        exit;
        if(isset($_GET['email'])){
		$data['emailunico'] = $_GET['email'];
		$data['cliente_fob'] = $_GET['name'];
		$data['name'] = $_GET['name'];
		
		$data['destino'] = $_GET['name'];
		$data['price'] = $_GET['name'];
		
		$cotacao = (new CotacoesModel())->find("id = :id", "id={$data['id']}")->fetch();
		$empresa = (new EmpresasModel())->find("id = :id", "id={$cotacao->cliente_id}")->fetch();
		$petrotrans = (new EmpresasModel())->find("name = :name", "name=PETROTRANS")->fetch();

		$data['msg'] = $this->view->render("cotacao", [
		    "title" => "Visualizar Cotação",
		    "cotacao" => $cotacao,
		    "empresa" => $empresa,
		    "petrotrans"=> $petrotrans
		]);
        }
	

        $this->mail = new CotacoesFacade();

        if(!$this->mail->mail($data)) {
            echo json_encode("Ocorreu um Erro" );
            return;
        }

        //LOGGER
        (new \Source\Support\Log("cotacao"))
            ->archive()
            ->info("Cotação Enviada!");

        echo json_encode("Cotação Enviada!");
    }
}
