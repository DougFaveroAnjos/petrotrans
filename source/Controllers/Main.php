<?php


namespace Source\Controllers;


use CoffeeCode\Paginator\Paginator;
use Source\Models\ColetasModel;
use Source\Models\ComentariosContatosModel;
use Source\Models\ContatosModel;
use Source\Models\ContatosEmpresaModel;
use Source\Models\CotacoesModel;
use Source\Models\EmailConfig;
use Source\Models\EmpresasModel;
use Source\Models\FiscalModel;
use Source\Models\MotoristasModel;
use Source\Models\MsgWhatsappModel;
use Source\Models\sendCronConfig;
use Source\Models\TransportesModel;
use Source\Models\Users;
use Source\Support\Email;
use Source\Support\Whatsapp;

class Main extends Controller
{
    protected  $user;
public function __construct($router)
{
    parent::__construct($router, "main");
    if(!empty($_SESSION['id'])){
        $this->user = (new Users())->findById($_SESSION['id']);
    }
}

public function home(): void
{
    echo $this->view->render("home", [
        "title" => "Bem Vindo",
    ]);
}

public function main():void
{

    if($_SESSION["logged"] != true) {
        header("Location:".url());
        return;
    }

    echo $this->view->render("main", [
        "title" => "Bem Vindo",
    ]);
}

public function perfil(): void
{
    if($_SESSION["logged"] != true) {
        header("Location:".url());
        return;
    }

    $user = (new Users())->find("id = :id", "id={$_SESSION['id']}")->fetch();

    echo $this->view->render("perfil", [
        "title" => "Editar Conta",
        "user" => $user
    ]);
}

public function whatsapp(?array $data): void
{
    if($_SESSION["logged"] != true) {
        header("Location:".url());
        return;
    }
    echo $this->view->render("whatsapp", [
        "title" => "Enviar Email",
        "data" => $data
    ]);
}
public function whatsappSend(array $data): void
{
    if(isset($data['whatsapps'])) {
        $whatsaps = explode("; ", $data['whatsapps']);
    } else {
        if(!empty($data['ddd'])){
            $whatsaps = [];
            $contatos = (new MotoristasModel())->find()->fetch(true);

            if($contatos) {
                foreach ($contatos as $contato) {
                    if(!empty($contato->telefone)){
                        $ddd = deixarNumero($contato->telefone);
                        if($data['ddd'] == substr($ddd, 0, 2)){
                            array_push($whatsaps,  $ddd);
                        }
                    }
                }
            }
        }else{
            $whatsaps = [];
            
            if(!empty($data['de']) && !empty($data['ate'])){
                if($data['de'] <= $data['ate']){
                    $de = date("Y-m-d", strtotime($data['de']));
                    $ate = date("Y-m-d", strtotime($data['ate']));

                    if($data['contatos'] !== "all"){
                        $contatos = (new ContatosModel())->find("status = :status AND date BETWEEN {$de} AND {$ate}", "status={$data['contatos']}")->fetch(true);
                    }else{
                        $contatos = (new ContatosModel())->find("date BETWEEN '{$de}' AND '{$ate}'")->fetch(true);
                    }
                    if($contatos) {
                        foreach ($contatos as $contato) {
                            if(!empty($contato->contato)){
                                foreach (explode("; ", $contato->contato) as $item) {
                                    array_push($whatsaps, deixarNumero($item));
                                }
                            }
                        }
                    }
                }else{
                    echo json_encode("A data inicial não pode ser maior que a data final");
                    return;
                }
            }else{
                echo json_encode("Defina a data de inicio e fim");
                return;
            }
        }
    }

    foreach ($whatsaps as $whatsap) {
        (new Whatsapp())->bootstrap(
            $whatsap,
            $data['conteudo']
        )->queue();

        //LOGGER
        (new \Source\Support\Log("enviar_whatsapp"))
            ->archive()
            ->info("Envio de whatsapp realizado para: {$whatsap}");
    }

    header("Location:".url("main/whatsapp"));
    return;
}

public function msgdefault(?array $data):void
{
    if(!empty($data)){
        if(!empty($data['tipo']) && $data['tipo'] == "delete"){
            $add = (new MsgWhatsappModel())->findById($data['id']);
            $add->destroy();

            //LOGGER
            (new \Source\Support\Log("mensagem_default"))
                ->archive()
                ->info("Mensagem deletada com sucesso: id: {$data['id']}");

            echo json_encode(["Mensagem deletada com sucesso"]);
            return;
        }

        if(!empty($data['tipo']) && $data['tipo'] == "create"){
            $add = (new MsgWhatsappModel());
            $add->content = $data['mensagem'];
            $add->type = $data['type'];
            $add->save();

            //LOGGER
            (new \Source\Support\Log("mensagem_default"))
                ->archive()
                ->info("Mensagem cadastrada com sucesso");

            echo json_encode(["Mensagem cadastrada com sucesso"]);
            return;
        }
    }

    $msg = (new MsgWhatsappModel())->find()->fetch(true);
    echo $this->view->render("criarMsg", [
        "title" => "Criar mensagem padrão whatsapp",
        "msg" => $msg
    ]);
}

public function configEmail(?array $data)
{
    $config = (new sendCronConfig())->find()->fetch(true);
    if(!empty($data)) {

        if(!empty($data['tipo']) && $data['tipo'] == "delete"){
            $add = (new sendCronConfig())->findById($data['id']);
            $add->destroy();

            //LOGGER
            (new \Source\Support\Log("configuracao_cron"))
                ->archive()
                ->info("Configuração deletada com sucesso: ID: {$data['id']}");

            echo json_encode(["Configuração deletada com sucesso"]);
            return;
        }


        if(!empty($data['tipo']) && $data['tipo'] == "create"){
            if($data['inicio'] > $data['final']){
                echo json_encode(["O horário de inficio não pode ser mario que a do término"]);
                return;
            }

            $cron = (new sendCronConfig())->find("type = :type","type={$data['type']}")->fetch();
            $dias = implode(";", $data['dias']);
            if(!$cron){
                $cron = (new sendCronConfig());
            }
            $cron->days = $dias;
            $cron->start = $data['inicio'];
            $cron->end = $data['final'];
            $cron->limite = $data['limite'];
            $cron->wait = $data['espera'];
            $cron->type = $data['type'];
            $cron->save();

            //LOGGER
            (new \Source\Support\Log("configuracao_cron"))
                ->archive()
                ->info("Configuração cadastrada com sucesso");

            echo json_encode(["Configuração cadastrada com sucesso"]);
            return;
        }
    }

    echo $this->view->render("cron", [
        "title" => "Configuração cron",
        "lista" => $config
    ]);
}

public function email(?array $data)
{
    if(!empty($data)){
        if(!empty($data['tipo']) && $data['tipo'] == "delete"){
            $add = (new EmailConfig())->findById($data['id']);
            $add->destroy();

            //LOGGER
            (new \Source\Support\Log("configuracao_email"))
                ->archive()
                ->info("Configuração deletada com sucesso: ID: {$data['id']}");

            echo json_encode(["Configuração deletada com sucesso"]);
            return;
        }

        if(!empty($data['tipo']) && $data['tipo'] == "create"){
            $add = (new EmailConfig());
            $add->host = $data['host'];
            $add->port = $data['port'];
            $add->user = $data['user'];
            $add->pass = $data['pass'];
            $add->name = $data['name'];
            $add->address = $data['address'];
            $add->address = $data['address'];
            $add->type = $data['type'];
            $add->signature = $data['signature'];
            $add->save();

            //LOGGER
            (new \Source\Support\Log("configuracao_email"))
                ->archive()
                ->info("Configuração de e-mail cadastrado com sucesso");

            echo json_encode(["Configuração cadastrada com sucesso"]);
            header("Location:".url("main/email"));
            return;
        }
    }

    $lista = (new EmailConfig())->find()->fetch(true);
    echo $this->view->render("email_config", [
        "title" => "Cofiguração de e-mail",
        "lista" => $lista
    ]);
}


public function apolice(?array $data)
{
    $lista = (new EmailConfig())->find()->fetch(true);
    $mail = array();
    $mail['anexos'][] = "theme/pdf/apolices/IMPAPLRES_10654340040000600000.pdf";
    $mail['anexos'][] = "theme/pdf/apolices/IMPAPLRES_10655340040000400000.pdf";
    $mail['anexos'][] = "theme/pdf/apolices/IMPCERT_10654340040000600000.pdf";
    $mail['anexos'][] = "theme/pdf/apolices/IMPCERT_10655340040000400000.pdf";

    if(!empty($data)){
        if(!empty($data['tipo']) && $data['tipo'] == "create"){
            $anexos = null;
            if($data['anexos']){
                $anexos = explode(";", $data['anexos']);
                $anexos = array_filter($anexos);
                $anexos = implode(";", $anexos);
            }

            $emails = explode(";", $data['emails']);
            foreach ($emails as $email) {
                $mail = (new Email())->bootstrap(
                    $data['assunto'],
                    $email,
                    $email,
                    $data['signature'],
                    "massa"
                );

                if($anexos){
                    $mail->attach($anexos, $anexos);
                }
                $mail->queue();

                //LOGGER
                (new \Source\Support\Log("apolice"))
                    ->archive()
                    ->info("E-mail enviado com sucesso! Email: {$email}");
            }
            echo json_encode(["E-mail enviado com sucesso"]);
            header("Location:".url("main/apolice"));
            return;
        }
    }

    echo $this->view->render("apolice", [
        "title" => "Apolice de seguro",
        "lista" => $lista,
        "mail" => $mail
    ]);
}

public function table(array $data):void
{
    if($_SESSION["logged"] != true) {
        header("Location:".url());
        return;
    }
    $empresa = null;
    if(!empty($this->user->empresa)){
        $empresaAND = " AND cliente_id = {$this->user->empresa}";
        $empresa = "cliente_id = {$this->user->empresa}";
    }

    $page = filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT);
    $pager = new Paginator(url("main/cotacoes?page="));
    $pager->pager((new CotacoesModel())->find($empresa)->count(), 10, $page, 1);

 //   echo "<script>alert('Cotacao $updater ')</script>";
    if(!isset($_GET['updater']) || $_GET['updater'] === "todos") {
        $updater = "";
    } else {
        $updater = $_GET['updater'];
    }
    if(!isset($_GET['vendedor']) || $_GET['vendedor'] === "todos") {
        $vendedor = "";
    } else {
        $vendedor = $_GET['vendedor'];
    }

        if(!array_key_exists("type", $_GET)) {

            if(array_key_exists("frete", $_GET)) {
                $cotacoes = (new CotacoesModel())->find("opcao_frete like :frete {$empresaAND}", "frete=%{$_GET['frete']}%")->order("created_at DESC")->limit($pager->limit())->offset($pager->offset())->fetch(true);
            } else {
                $cotacoes = (new CotacoesModel())->find($empresa)->order("created_at DESC")->limit($pager->limit())->offset($pager->offset())->fetch(true);
            }
        } else if($_GET['type'] === "vendedor") {
            $cotacoes = (new CotacoesModel())->find("vendedor like :vendedor AND opcao_frete like :frete {$empresaAND}", "vendedor=%{$vendedor}%&frete=%{$_GET['frete']}%")->order("id DESC")->fetch(true);
//       } else if($_GET['type'] === "updater") {
//            $cotacoes = (new CotacoesModel())->find("updater like :updater AND opcao_frete like :frete", "updater=%{$updater}%&frete=%{$_GET['frete']}%")->order("id DESC")->fetch(true);
       } else if($_GET['type'] === "id") {
            $cotacoes = (new CotacoesModel())->find("id = :id AND opcao_frete like :frete {$empresaAND}", "id={$_GET['id']}&frete=%{$_GET['frete']}%")->order("id DESC")->fetch(true);
        } else if($_GET['type'] === "data") {
            $cotacoes = (new CotacoesModel())->find("date = :data AND opcao_frete like :frete {$empresaAND}", "data={$_GET['data']}&frete=%{$_GET['frete']}%")->order("id DESC")->fetch(true);
        } else if($_GET['type'] === "cliente") {
            $cotacoes = (new CotacoesModel())->find("cliente like :cliente OR cliente_fob like :cliente AND opcao_frete like :frete {$empresaAND}", "cliente=%{$_GET['cliente']}%&frete=%{$_GET['frete']}%")->order("id DESC")->fetch(true);
        } else if($_GET['type'] === "valor") {
            $cotacoes = (new CotacoesModel())->find("valor_cotacao >= :min_valor AND valor_cotacao <= :max_valor AND opcao_frete like :frete {$empresaAND}", "min_valor={$_GET['min-valor']}&max_valor={$_GET['max-valor']}&frete=%{$_GET['frete']}%")->order("id DESC")->fetch(true);
        } else if($_GET['type'] === "km") {
            $cotacoes = (new CotacoesModel())->find("km >= :min_km AND km <= :max_km AND opcao_frete like :frete {$empresaAND}", "min_km={$_GET['min-km']}&max_km={$_GET['max-km']}&frete=%{$_GET['frete']}%")->order("id DESC")->fetch(true);
        } else if($_GET['type'] === "tipo") {
            $cotacoes = (new CotacoesModel())->find("tipo_carga = :tipo AND opcao_frete like :frete {$empresaAND}", "tipo={$_GET['tipo']}&frete=%{$_GET['frete']}%")->order("id DESC")->fetch(true);
        } else if($_GET['type'] === "status") {
            $cotacoes = (new CotacoesModel())->find("status = :status AND opcao_frete like :frete {$empresaAND}", "status={$_GET['status']}&frete=%{$_GET['frete']}%")->order("id DESC")->fetch(true);
        } else if($_GET['type'] === "origem") {
            $cotacoes = (new CotacoesModel())->find("cidade_origem like :origem OR uf_origem like :origem {$empresaAND}",
            "origem=%{$_GET['origem']}%")->fetch(true);
        } else if($_GET['type'] === "destino") {
            $cotacoes = (new CotacoesModel())->find("cidade_destino like :destino OR uf_destino like :destino {$empresaAND}",
                "destino=%{$_GET['destino']}%")->fetch(true);
        } else {
            $cotacoes = [];
        }

    $users = (new Users())->find()->fetch(true);
    $empresas = (new EmpresasModel())->find()->fetch(true);
    $transportes = (new TransportesModel())->find()->fetch(true);

    echo $this->view->render("table", [
        "title" => "Cotações",
        "pager" => $pager->render(),
        "users" => $users,
        "cotacoes" => $cotacoes,
        "empresas" => $empresas,
        "transportes" => $transportes,
        "userE" => ((new Users())->findById($_SESSION['id'])->empresa ? false: true)
    ]);
}

public function empresas():void
{
    if($_SESSION["logged"] != true) {
        header("Location:".url());
        return;
    }

    $page = filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT);
    $pager = new Paginator(url("main/empresas?page="));
    $pager->pager((new EmpresasModel())->find()->count(), 10, $page, 1);

    if(!array_key_exists("nome", $_GET) || $_GET['nome'] === "" && $_GET['status'] === "todos") {
        $empresas = (new EmpresasModel())->find()->order("name ASC")->limit($pager->limit())->offset($pager->offset())->fetch(true);
    } else if($_GET['nome'] !== "" && $_GET['status'] !== "todos") {
        $empresas = (new EmpresasModel())->find("name like '{$_GET['nome']}%' AND status = :status", "status={$_GET['status']}")->order("name ASC")->fetch(true);
    } else if($_GET['nome'] !== "" && $_GET['status'] === "todos") {
        $empresas = (new EmpresasModel())->find("name like '{$_GET['nome']}%'")->order("name ASC")->fetch(true);
    } else if($_GET['nome'] === "" && $_GET['status'] !== "todos") {
        $empresas = (new EmpresasModel())->find("status = :status", "status={$_GET['status']}")->order("name ASC")->fetch(true);
    } else {
        $empresas = [];
    }

    $liberados = (new TransportesModel())->find("liberacao = :liberado", "liberado=liberado")->order("data_liberacao ASC")->fetch(true);
    $users = (new Users())->find("id = :id", "id={$_SESSION['id']}")->fetch();

    echo $this->view->render("empresas", [
        "title" => "Empresas",
        "pager" => $pager->render(),
        "liberados" => $liberados,
        "users" => $users,
        "empresas" => $empresas
    ]);
}

public function contatos(): void {

    if($_SESSION["logged"] != true) {
        header("Location:".url());
        return;
    }

    $page = filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT);
    $pager = new Paginator(url("main/contatos?page="));
    $pager->pager((new ContatosModel())->find()->count(), 30, $page, 1);


    if(isset($_GET['data_min']) && $_GET['data_min'] !== "") {
        $datamin = $_GET['data_min'];
        $datamax = $_GET['data_max'];
    } else {
        $datamin = "2000-01-01";
        $datamax = "3000-12-30";
    }

    if(array_key_exists("usuario", $_GET) && $_GET['usuario'] !== "todos") {
        $usuario = $_GET['usuario'];
    } else {
        $usuario = "";
    }
    
    if(isset($usuario)){
        $contatos = (new ContatosModel())->find("user_name = :name", "name={$usuario}")->order("date DESC")->limit($pager->limit())->offset($pager->offset())->fetch(true);
 //       echo "<script>alert('Filtro 1 ')</script>";
    }
    if(!array_key_exists("nome", $_GET)) {
  //      echo "<script>alert('Filtro 2 ')</script>";
        $contatos = (new ContatosModel())->find("user_name = :name", "name={$_SESSION['name']}")->order("date DESC")->limit($pager->limit())->offset($pager->offset())->fetch(true);
    } else if($_GET['nome'] !== "") {
//        $temp="usuario=%{$usuario}%&empresa=%{$_GET['nome']}%";
//        echo "<script>alert('Filtro $temp ')</script>";
        $contatos = (new ContatosModel())->find("user_name = :usuario AND empresa like :empresa", "usuario={$usuario}&empresa=%{$_GET['nome']}%")->order("date DESC")->fetch(true);
    } else if($_GET['data_min'] !== "") {
//        echo "<script>alert('Filtro 3 ')</script>";
        $contatos = (new ContatosModel())->find("user_name = :usuario AND date(date) >= :datein AND date(date) <= :datemax", "usuario={$usuario}&datein={$_GET['data_min']}&datemax={$_GET['data_max']}")->order("date DESC")->fetch(true);
    }else if($_GET['status'] !== "todos") {
  //             echo "<script>alert('Filtro 4 ')</script>";
        $contatos = (new ContatosModel())->find("user_name = :usuario AND status like :status", "usuario={$usuario}&status={$_GET['status']}%")->order("date DESC")->fetch(true);
    }


    $comentarios = (new ComentariosContatosModel())->find()->order("created_at DESC")->fetch(true);
    $liberados = (new TransportesModel())->find("liberacao = :liberado", "liberado=liberado")->order("data_liberacao ASC")->fetch(true);
    $users = (new Users())->find()->fetch(true);

    echo $this->view->render("contatos", [
        "title" => "Contatos",
        "users" => $users,
        "pager" => $pager->render(),
        "contatos" => $contatos,
        "liberados" => $liberados,
        "comentarios" => $comentarios
    ]);

}

public function contatosempresa(): void {

    if($_SESSION["logged"] != true) {
        header("Location:".url());
        return;
    }
	$cnpj = $_GET['cnpj'];
        $contatos = (new ContatosEmpresaModel())->find("cnpj = :cnpj", "cnpj={$cnpj}");
 

    echo $this->view->render("contatosempresa", [
        "title" => "ContatosEmpresa",
        "contatosempresa" => $contatos
    ]);

}

public function transportes(array $data): void
{
    if($_SESSION["logged"] != true) {
        header("Location:".url());
        return;
    }

    if($this->user->empresa){
        header("Location:".url("main/transportes-cliente?categoria=liberado"));
    }

    $page = filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT);
    $pager = new Paginator(url("main/transportes?categoria={$_GET['categoria']}&page="));
    $pager->pager((new TransportesModel())->find()->count(), 50, $page, 1);

    if(!isset($_GET['cotacao-id']) && $_GET['categoria'] === 'finalizado') {
        $liberados = (new TransportesModel())->find("liberacao like :liberacao", "liberacao=%{$_GET['categoria']}%")->order("data_liberacao DESC")->limit($pager->limit())->offset($pager->offset())->fetch(true);
    } else if(!isset($_GET['cotacao-id']) && $_GET['categoria'] !== 'finalizado') {
        $liberados = (new TransportesModel())->find("liberacao like '{$_GET['categoria']}'")->fetch(true);
    } else if($_GET['cotacao-id'] !== "none") {
        $liberados = (new TransportesModel())->find("liberacao like :liberacao AND cotacao_id = :id", "liberacao=%{$_GET['categoria']}%&id={$_GET['cotacao-id']}")->fetch(true);
    } else if($_GET['cte'] !== "") {
        $_GET['cte'] = "CTe ".$_GET['cte']."";
        $liberados = (new TransportesModel())->find("liberacao like :liberacao AND status like :cte",
            "liberacao=%{$_GET['categoria']}%&cte=%{$_GET['cte']}%")->fetch(true);
    } else if($_GET['empresa'] !== "") {
        $liberados = (new TransportesModel())->find("liberacao like :liberacao AND empresa_name = :empresa", "liberacao=%{$_GET['categoria']}%&empresa={$_GET['empresa']}")->fetch(true);
    } else {
        $liberados = (new TransportesModel())->find("liberacao like :liberacao AND origem like :origem AND destino like :destino","liberacao=%{$_GET['categoria']}%&origem=%{$_GET['origem']}%&destino=%{$_GET['destino']}%")->fetch(true);
    }

    $cotacoes = (new CotacoesModel())->find()->fetch(true);
    $motoristas = (new MotoristasModel())->find()->fetch(true);

    echo $this->view->render("transportes", [
        "title" => "Transportes",
        "pager" => $pager->render(),
        "liberados" => $liberados,
        "motoristas" => $motoristas,
        "cotacoes" => $cotacoes,
    ]);
}

public function transportesCliente(array $data): void
{
    if($_SESSION["logged"] != true) {
        header("Location:".url());
        return;
    }

    $empresa = (new EmpresasModel())->find("name = :name", "name={$_SESSION['name']}")->fetch();
    $page = filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT);
    $pager = new Paginator(url("main/fiscal?page="));
    $pager->pager((new FiscalModel())->find("empresa = :empresa", "empresa={$empresa->name}")->count(), 10, $page, 1);

    if(!isset($_GET['submit'])) {
        $fiscais = (new FiscalModel())->find("empresa = :empresa", "empresa={$empresa->name}")->limit($pager->limit())->offset($pager->offset())->fetch(true);
    } else if($_GET['cte'] !== "") {
        $fiscais = (new FiscalModel())
            ->find("empresa = :empresa AND cte like :cte OR cte like :Ucte OR cte like :Icte OR cte like :Acte",
                "empresa={$empresa->name}&cte=% {$_GET['cte']} %&Ucte={$_GET['cte']}&Icte={$_GET['cte']} %&Acte=% {$_GET['cte']}")
            ->fetch(true);
    } else if($_GET['mdfe'] !== "") {
        $fiscais = (new FiscalModel())
            ->find("empresa = :empresa AND mdfe like :mdfe OR mdfe like :Umdfe OR mdfe like :Imdfe OR mdfe like :Amdfe",
                "empresa={$empresa->name}&mdfe=% {$_GET['mdfe']} %&Umdfe={$_GET['mdfe']}&Imdfe={$_GET['mdfe']} %&Amdfe=% {$_GET['mdfe']}")
            ->fetch(true);
    } else {
        $fiscais = (new FiscalModel())
            ->find("empresa = :empresa AND motorista like :motorista AND origem like :origem AND destino like :destino",
                "empresa={$empresa->name}&motorista=%{$_GET['motorista']}%&origem=%{$_GET['origem']}%&destino=%{$_GET['destino']}%")->fetch(true);
    }

    echo $this->view->render("transportesCliente", [
        "title" => "Fiscal",
        "fiscais" => $fiscais,
        "pager" => $pager->render()
    ]);
}

public function motoristas(): void
{
    if($_SESSION["logged"] != true) {
        header("Location:".url());
        return;
    }

    $page = filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT);
    $pager = new Paginator(url("main/motoristas?page="));
    $pager->pager((new MotoristasModel())->find()->count(), 10, $page, 1);

    if(isset($_GET['nome'])  && $_GET['nome'] !== ""){
//        echo "<script>alert('Filtro nome ')</script>";
        $motoristas = (new MotoristasModel())->find("name like :name", "name=%{$_GET['nome']}%")->order("name ASC")->fetch(true);
    } else if(isset($_GET['cpf']) && $_GET['cpf'] !== ""){
//        echo "<script>alert('Filtro cpf')</script>";
        $motoristas = (new MotoristasModel())->find("cpf like :cpf", "cpf={$_GET['cpf']}%")->order("name ASC")->fetch(true);
    } else if(isset($_GET['veiculo']) && $_GET['veiculo'] !== ""){
//        echo "<script>alert('Filtro placa')</script>";
        $motoristas = (new MotoristasModel())->find("placa_veiculo like :placa OR placa_reboque like :placa", "placa={$_GET['veiculo']}%")->order("name ASC")->fetch(true);
    } else {
//        echo "<script>alert('sem filtro ')</script>";
        $motoristas = (new MotoristasModel())->find()->order("name ASC")->limit($pager->limit())->offset($pager->offset())->fetch(true);

        //        $motoristas = (new MotoristasModel())->find("name like :name AND cpf like :cpf AND placa_veiculo like :placa OR placa_reboque like :placa", "name=%{$_GET['nome']}%&cpf={$_GET['cpf']}%&placa={$_GET['veiculo']}%")->order("name ASC")->fetch(true);
    }

    echo $this->view->render("motoristas", [
        "title" => "Motoristas",
        "motoristas" => $motoristas,
        "pager" => $pager->render(),
    ]);

}

public function coletas(): void
{
    if($_SESSION["logged"] != true) {
        header("Location:".url());
        return;
    }

    $transportes = (new TransportesModel())->find()->fetch(true);
    $motoristas = (new MotoristasModel())->find()->fetch(true);

    $page = filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT);
    $pager = new Paginator(url("main/coletas?page="));
    $pager->pager((new ColetasModel())->find()->count(), 10, $page, 1);

    if(!isset($_GET['motorista'])) {
        $coletas = (new ColetasModel())->find()->order("id DESC")->limit($pager->limit())->offset($pager->offset())->fetch(true);
    } else {
        if($_GET['oc'] === "none") {
            if(!empty($_GET['motorista']) && empty($_GET['placa'])){
                $coletas = (new ColetasModel())->find("motorista like '{$_GET['motorista']}%'")->order("id DESC")->fetch(true);
            }else if(empty($_GET['motorista']) && !empty($_GET['placa'])){
                $coletas = (new ColetasModel())->find("placa_veiculo like '{$_GET['placa']}%' OR placa_reboque like '{$_GET['placa']}%'")->order("id DESC")->fetch(true);
            }else{
                $coletas = (new ColetasModel())->find("motorista like '{$_GET['motorista']}%' OR placa_veiculo like '{$_GET['placa']}%' OR placa_reboque like '{$_GET['placa']}%'")->order("id DESC")->fetch(true);
            }
        } else {
            $coletas = (new ColetasModel())->find("coleta_id = :id", "id={$_GET['oc']}")->fetch(true);
        }
    }

    echo $this->view->render("coletas", [
        "title" => "Ordem de Coleta",
        "coletas" => $coletas,
        "transportes" => $transportes,
        "motoristas" => $motoristas,
        "pager" => $pager->render()
    ]);
}

public function global(): void
{
    if($_SESSION["logged"] != true) {
        header("Location:".url());
        return;
    }


    if(!isset($_GET['submit'])) {

        $transportes = (new TransportesModel())->find("created_at BETWEEN CURDATE() - INTERVAL 45 DAY AND CURDATE() + INTERVAL 45 DAY")
            ->order("cotacao_id DESC")->fetch(true);

    } else if($_GET['cte'] !== "") {

        $transportes = (new TransportesModel())
            ->find("cte like :cte OR cte like :Ucte OR cte like :Icte OR cte like :Acte",
                "cte=% {$_GET['cte']} %&Ucte={$_GET['cte']}&Icte={$_GET['cte']} %&Acte=% {$_GET['cte']}")
            ->order("status ASC, data_liberacao DESC")->fetch(true);

    } else if($_GET['coleta_min'] !== "" || $_GET['coleta_max'] !== "") {

        $transportes = (new TransportesModel())
            ->find("data_liberacao BETWEEN :coleta_min AND :coleta_max",
                "coleta_min={$_GET['coleta_min']}&coleta_max={$_GET['coleta_max']}")
            ->order("status ASC, data_liberacao DESC")->fetch(true);

    } else if($_GET['coleta_min'] === "" && $_GET['coleta_max'] === "" && $_GET['cte'] === "" && $_GET['empresa'] === ""
        && $_GET['motorista'] === "" && $_GET['origem'] === "" && $_GET['destino'] === "") {

        $transportes = (new TransportesModel())->find()->order("status ASC, data_finalizado DESC")->fetch(true);

    } else if($_GET['motorista'] !== "") {

        $transportes = (new TransportesModel())
            ->find("origem like :origem AND destino like :destino AND motorista_name like :motorista AND empresa_name like :empresa",
                "motorista=%{$_GET['motorista']}%&empresa=%{$_GET['empresa']}%&origem=%{$_GET['origem']}%&destino=%{$_GET['destino']}%")
            ->order("status ASC, data_liberacao ASC")->fetch(true);

    } else {

        $transportes = (new TransportesModel())
            ->find("origem like :origem AND destino like :destino AND empresa_name like :empresa",
            "empresa=%{$_GET['empresa']}%&origem=%{$_GET['origem']}%&destino=%{$_GET['destino']}%")
            ->order("status ASC, data_liberacao ASC")->fetch(true);

    }

    echo $this->view->render("global", [
        "title" => "Listagem Global",
       "transportes" => $transportes,
    ]);
}

public function fiscal(): void
{
    if($_SESSION["logged"] != true) {
        header("Location:".url());
        return;
    }

    $page = filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT);
    $pager = new Paginator(url("main/fiscal?page="));

    if(!isset($_GET['submit'])) {
        $pager->pager((new FiscalModel())->findTransportes()->count(), 10, $page, 1);
        $fiscais = (new FiscalModel())->findTransportes()->limit($pager->limit())->offset($pager->offset())->order("liberacao ASC")->fetch(true);
    } else if($_GET['cte'] !== "") {
        $pager->pager((new FiscalModel())->findTransportes("f.cte like :cte OR f.cte like :Ucte OR f.cte like :Icte OR f.cte like :Acte",
            "cte=%{$_GET['cte']}%&Ucte={$_GET['cte']}&Icte={$_GET['cte']} %&Acte=% {$_GET['cte']}")
            ->order("liberacao ASC
            ")->count(), 10, $page, 1);

        $fiscais = (new FiscalModel())
            ->findTransportes("f.cte like :cte OR f.cte like :Ucte OR f.cte like :Icte OR f.cte like :Acte",
                "cte=%{$_GET['cte']}%&Ucte={$_GET['cte']}&Icte={$_GET['cte']} %&Acte=% {$_GET['cte']}")
            ->order("liberacao ASC")->fetch(true);
    } else if($_GET['mdfe'] !== "") {

        $pager->pager((new FiscalModel())->findTransportes("f.mdfe like :mdfe OR f.mdfe like :Umdfe OR f.mdfe like :Imdfe OR f.mdfe like :Amdfe",
            "mdfe=% {$_GET['mdfe']} %&Umdfe={$_GET['mdfe']}&Imdfe={$_GET['mdfe']} %&Amdfe=% {$_GET['mdfe']}")
            ->order("liberacao ASC")->count(), 10, $page, 1);


        $fiscais = (new FiscalModel())
            ->findTransportes("f.mdfe like :mdfe OR f.mdfe like :Umdfe OR f.mdfe like :Imdfe OR f.mdfe like :Amdfe",
                "mdfe=% {$_GET['mdfe']} %&Umdfe={$_GET['mdfe']}&Imdfe={$_GET['mdfe']} %&Amdfe=% {$_GET['mdfe']}")
            ->order("liberacao ASC")->fetch(true);
    } else {
        if(!empty($_GET['empresa']) || !empty($_GET['motorista']) || !empty($_GET['origem']) ||  !empty($_GET['destino'])){

            $pager->pager((new FiscalModel())->findTransportes("f.empresa like :empresa AND f.motorista like :motorista AND f.origem like :origem AND f.destino like :destino",
                "empresa={$_GET['empresa']}%&motorista={$_GET['motorista']}%&origem={$_GET['origem']}%&destino={$_GET['destino']}%")->count(), 10, $page, 1);


            $fiscais = (new FiscalModel())
                ->findTransportes("f.empresa like :empresa AND f.motorista like :motorista AND f.origem like :origem AND f.destino like :destino",
                    "empresa={$_GET['empresa']}%&motorista={$_GET['motorista']}%&origem={$_GET['origem']}%&destino={$_GET['destino']}%")->order("liberacao ASC")->fetch(true);
        }else{
            $fiscais = null;
        }
    }


    echo $this->view->render("fiscal", [
        "title" => "Fiscal",
        "fiscais" => $fiscais,
        "pager" => $pager->render()
    ]);
}

public function financeiroPagar(): void
{

    if($_SESSION["logged"] != true) {
        header("Location:".url());
        return;
    }

    $page = filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT);
    $pager = new Paginator(url("main/financeiroPagar?page="));
    $pager->pager((new FiscalModel())->find()->count(), 10, $page, 1);

    if(!isset($_GET['submit'])) {
        $fiscais = (new FiscalModel())->find()->order("id DESC")->limit($pager->limit())->offset($pager->offset())->fetch(true);
    } else if($_GET['cte'] !== "") {
        $fiscais = (new FiscalModel())->find("cte like :cte OR cte like :Ucte OR cte like :Icte OR cte like :Acte",
            "cte=% {$_GET['cte']} %&Ucte={$_GET['cte']}&Icte={$_GET['cte']} %&Acte=% {$_GET['cte']}")->fetch(true);
    } else if($_GET['mdfe'] !== "") {
        $fiscais = (new FiscalModel())->find("mdfe like :mdfe OR mdfe like :Umdfe OR mdfe like :Imdfe OR mdfe like :Amdfe",
            "mdfe=% {$_GET['mdfe']} %&Umdfe={$_GET['mdfe']}&Imdfe={$_GET['mdfe']} %&Amdfe=% {$_GET['mdfe']}")->fetch(true);
    } else {
        $fiscais = (new FiscalModel())->find("empresa like :empresa AND motorista like :motorista",
            "empresa=%{$_GET['empresa']}%&motorista=%{$_GET['motorista']}%")->fetch(true);
    }

    echo $this->view->render("financeiroPagar", [
        "title" => "Financeiro",
        "fiscais" => $fiscais,
        "pager" => $pager->render()
    ]);

}

    public function financeiroReceber(): void
    {

        if($_SESSION["logged"] != true) {
            header("Location:".url());
            return;
        }

        $page = filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT);
        $pager = new Paginator(url("main/financeiroReceber?page="));
        $pager->pager((new FiscalModel())->find()->count(), 10, $page, 1);

        if(!isset($_GET['submit'])) {
            $fiscais = (new FiscalModel())->find()->limit($pager->limit())->offset($pager->offset())->fetch(true);
        } else if($_GET['cte'] !== "") {
            $fiscais = (new FiscalModel())->find("cte like :cte OR cte like :Ucte OR cte like :Icte OR cte like :Acte",
                "cte=% {$_GET['cte']} %&Ucte={$_GET['cte']}&Icte={$_GET['cte']} %&Acte=% {$_GET['cte']}")->order("id DESC")->fetch(true);
        } else if($_GET['mdfe'] !== "") {
            $fiscais = (new FiscalModel())->find("mdfe like :mdfe OR mdfe like :Umdfe OR mdfe like :Imdfe OR mdfe like :Amdfe",
                "mdfe=% {$_GET['mdfe']} %&Umdfe={$_GET['mdfe']}&Imdfe={$_GET['mdfe']} %&Amdfe=% {$_GET['mdfe']}")->fetch(true);
        } else {
            $fiscais = (new FiscalModel())->find("empresa like :empresa AND motorista like :motorista",
                "empresa=%{$_GET['empresa']}%&motorista=%{$_GET['motorista']}%")->fetch(true);
        }

        echo $this->view->render("financeiroReceber", [
            "title" => "Financeiro",
            "fiscais" => $fiscais,
            "pager" => $pager->render()
        ]);

    }

    public function usuarios(?array $data): void
    {
        if($_SESSION["logged"] != true) {
            header("Location:".url());
            return;
        }

        $page = filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT);
        $pager = new Paginator(url("main/aprovar?page="));
        $pager->pager((new Users())->find("empresa IS NULL")->count(), 10, $page, 1);
        $user = (new Users())->find("empresa IS NULL")->limit($pager->limit())->offset($pager->offset())->fetch(true);

        echo $this->view->render("usuarios", [
            "title" => "Usuários",
            "users" => $user,
            "pager" => $pager->render()
        ]);
    }

    public function usuariosPerfil(?array $data): void
    {

        if(!empty($data['number'])){
            $id = $data['id'];
            $cUser = (new Users())->findById($id);
            $cUser->first_name = $data['first_name'];
            $cUser->last_name = $data['last_name'];
            $cUser->number = $data['number'];
            $cUser->save();

            //LOGGER
            (new \Source\Support\Log("usuarios"))
                ->archive()
                ->info("Usuário alterado com sucesso: e-mail: {$cUser->email}");
        }

        if($_SESSION["logged"] != true) {
            header("Location:".url());
            return;
        }

        $page = filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT);
        $pager = new Paginator(url("main/aprovar?page="));
        $pager->pager((new Users())->find("id = :id", "id={$data['id']}")->count(), 10, $page, 1);
        $user = (new Users())->find("id = :id", "id={$data['id']}")->limit($pager->limit())->offset($pager->offset())->fetch();

        echo $this->view->render("usuariosPerfil", [
            "title" => "Usuários",
            "user" => $user,
            "pager" => $pager->render()
        ]);
    }

    public function aprovar(?array $data): void
    {
        if(!empty($data)){
            $data = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $type = $data['type'];
            $id = $data['id'];

            $cUser = (new Users())->findById($id);
            if($cUser){
                if($type == "approved"){
                    $cUser->status = 'approved';
                    $cUser->save();

                    //LOGGER
                    (new \Source\Support\Log("aprovar"))
                        ->archive()
                        ->info("Usuário aprovado com sucesso! email: {$cUser}");
                }else{
                    //LOGGER
                    (new \Source\Support\Log("aprovar"))
                        ->archive()
                        ->info("Usuário reprovado com sucesso! email: {$cUser}");
                    $cUser->destroy();
                }
            }
        }

        if($_SESSION["logged"] != true) {
            header("Location:".url());
            return;
        }

        $page = filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT);
        $pager = new Paginator(url("main/aprovar?page="));
        $pager->pager((new Users())->find("status = 'waiting'")->count(), 10, $page, 1);
        $user = (new Users())->find("status = 'waiting'")->limit($pager->limit())->offset($pager->offset())->fetch(true);

        echo $this->view->render("aprovar", [
            "title" => "Aprovar",
            "users" => $user,
            "pager" => $pager->render()
        ]);
    }

    public function cadastrar(?array $data): void
    {
        if($_SESSION["logged"] != true) {
            header("Location:".url());
            return;
        }

        $data = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        if(!empty($data)){
            $empresa = (new EmpresasModel())->findById($data['empresa']);
            $user = (new Users())->find("empresa = :id", "id={$data['empresa']}");
            if(!empty($empresa) && !$user->fetch()){
                $user = (new Users());
                $user->first_name = $empresa->name;
                $user->last_name = $empresa->responsavel;
                $user->email = deixarNumero($empresa->cnpj);
                $user->password = $data['password'];
                $user->role = 1;
                $user->status = $data['status'];
                $user->empresa = $data['empresa'];
                if(!$user->save()){
                    setcookie("errormsg", "<div class='msg error on'>Erro ao criar usuário</div>", (time() + 3), "/");
                }else{
                    //LOGGER
                    (new \Source\Support\Log("cadastro_cliente"))
                        ->archive()
                        ->info("Cliente cadastrado com sucesso");
                    setcookie("errormsg", "<div class='msg success on'>Usuário cadastrado com sucesso</div>", (time() + 3), "/");
                }
                header("Location:".url("/main/cadastrar"));
                return;
            }
            setcookie("errormsg", "<div class='msg error on'>Usuário ja se encontra cadastrado</div>", (time() + 3), "/");
            header("Location:".url("/main/cadastrar"));
            return;
        }

        echo $this->view->render("cadastrar", [
            "title" => "Cadastrar",
            "empresas" => (new EmpresasModel())->find()->fetch(true),
            "users" => (new Users())->find("empresa IS NOT NULL")->fetch(true)
        ]);
    }

    public function configuracoes(): void
    {

        if($_SESSION["logged"] != true) {
            header("Location:".url());
            return;
        }

        $page = filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT);
        $pager = new Paginator(url("main/configuracoes"));
        
        header("Location:".pager());

    }

public function error(array $data): void
{
    echo $this->view->render("error", [
        "error" => $data["errcode"]
    ]);
}
}
