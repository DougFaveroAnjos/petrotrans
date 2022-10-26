<?php
session_start();
ob_start();

require __DIR__."/vendor/autoload.php";

use CoffeeCode\Router\Router;

$router = new Router(URL_BASE);

$router->namespace("Source\Controllers");

/* * Login
*/
$router->group(null);
$router->get("/", "Main:home", "Main.home");

$router->group("cliente");
$router->get("/", "Login:cliente", "Login.login");

$router->group("sistema");
$router->get("/cliente", "Login:login", "Login.login");
$router->get("/", "Login:login", "Login.login");
$router->get("/register", "Login:register", "Login.register");
$router->get("/logout", "Login:logout", "Login.logout");
$router->get("/recover", "Login:recover", "Login.recover");
$router->get("/new-password", "Login:newpass", "Login.newpass");

/*
* Auth
*/
$router->group("auth");
$router->post("/", "Auth:auth", "Auth.auth");
$router->post("/recover-mail", "Auth:recover", "Auth.recover" );

/*
* Register
*/
$router->group("create");
$router->post("/", "Register:create", "Register.create");
$router->post("/new-password", "Register:newpass", "Register.newpass");
$router->post("/edit/{id}", "Register:edit", "Register.edit");

/*
* Main
*/
$router->group("main");
$router->get("/", "Main:main", "Main.main");
$router->get("/perfil", "Main:perfil", "Main.perfil");
$router->get("/usuarios", "Main:usuarios", "Main.usuarios");
$router->get("/usuarios/{id}", "Main:usuariosPerfil", "Main.usuariosPerfil");
$router->post("/usuarios/{id}", "Main:usuariosPerfil", "Main.usuariosPerfil");
$router->get("/cotacoes", "Main:table", "Main.table");
$router->get("/empresas", "Main:empresas", "Main.empresas");
$router->get("/contatos", "Main:contatos", "Main.contatos");
$router->get("/transportes", "Main:transportes", "Main.transportes");
$router->get("/transportes-cliente", "Main:transportesCliente", "Main.transportesCliente");
$router->get("/motoristas", "Main:motoristas", "Main.motoristas");
$router->get("/coletas", "Main:coletas", "Main.coletas");
$router->get("/global", "Main:global", "Main.global");
$router->get("/fiscal", "Main:fiscal", "Main.fiscal");
$router->get("/apolice", "Main:apolice", "Main.apolice");
$router->post("/apolice", "Main:apolice", "Main.apolice");
$router->get("/financeiroPagar", "Main:financeiroPagar", "Main.financeiroPagar");
$router->get("/financeiroReceber", "Main:financeiroReceber", "Main.financeiroReceber");
$router->get("/aprovar", "Main:aprovar", "Main.aprovar");
$router->post("/aprovar", "Main:aprovar", "Main.aprovar");
$router->get("/cadastrar", "Main:cadastrar", "Main.cadastrar");
$router->post("/cadastrar", "Main:cadastrar", "Main.cadastrar");
$router->get("/msgdefault", "Main:msgdefault", "Main.msgdefault");
$router->post("/msgdefault", "Main:msgdefault", "Main.msgdefault");
$router->get("/email", "Main:email", "Main.email");
$router->post("/email", "Main:email", "Main.email");
$router->get("/config-email", "Main:configEmail", "Main.configEmail");
$router->post("/config-email", "Main:configEmail", "Main.configEmail");
$router->get("/whatsapp", "Main:whatsapp", "Main.whatsapp");
$router->post("/whatsapp", "Main:whatsappSend", "Main.whatsappSend");

/*
* Cotações
*/
$router->group("cotacao");
$router->get("/", "Cotacoes:criarCotacao", "Cotacoes.create");
$router->get("/edit/{id}", "Cotacoes:editCotacao", "Cotacoes.edit");
$router->get("/{id}", "Cotacoes:visualizar", "Cotacoes.visualizar");

$router->post("/analise", "Cotacoes:analisar", "Cotacoes.analise");
$router->post("/new", "Cotacoes:new", "Cotacoes.new");
$router->post("/delete/{id}", "Cotacoes:delete", "Cotacoes.delete");
$router->post("/update", "Cotacoes:update", "Cotacoes.update");
$router->post("/duplicate/{id}", "Cotacoes:duplicate", "Cotacoes.duplicate");

$router->post("/mail", "Cotacoes:mail", "Cotacoes.mail");

/*
* Empresas
*/
$router->group("empresa");
$router->get("/", "Empresas:criarEmpresa", "Empresas.criarEmpresa");
$router->get("/edit/{id}", "Empresas:editEmpresa", "Empresas.editEmpresa");
$router->get("/petrotrans", "Empresas:petrotrans", "Empresas.petrotrans");

$router->post("/new", "Empresas:new", "Empresas.new");
$router->post("/delete/{id}", "Empresas:delete", "Empresas.delete");
$router->post("/edit", "Empresas:edit", "Empresas.edit");
$router->post("/search", "Empresas:search", "Empresas.search");

$router->post("/consulta", "Empresas:consulta", "Empresas.consulta");

/*
* Contatos
*/
$router->group("contato");
$router->get("/", "Contato:criarContato", "Contato.criarContato");
$router->get("/edit/{id}", "Contato:editContato", "Contato.editContato");

$router->post("/new", "Contato:new", "Contato.new");
$router->post("/delete/{id}", "Contato:delete", "Contato.delete");
$router->post("/edit", "Contato:edit", "Contato.edit");
$router->post("/search", "Contato:search", "Contato.search");

/*
* Comentarios
*/
$router->group("comentarioscontatos");
$router->post("/", "ComentariosContatos:render", "ComentariosContatos.render");
$router->post("/new", "ComentariosContatos:new", "ComentariosContatos.new");

/*
* Transportes
*/
$router->group("transportes");
$router->get("/finalizar/{id}", "Transportes:finalizar", "Transportes.finalizar");
$router->get("/devolucao/{id}", "Transportes:devolucao", "Transportes.devolucao");

$router->post("/new", "Transportes:new", "Transportes.new");
$router->post("/confirm/{id}", "Transportes:confirm", "Transportes.confirm");
$router->post("/refresh", "Transportes:refresh", "Transportes.refresh");
$router->post("/delete", "Transportes:delete", "Transportes.delete");
$router->post("/duplicate/{id}", "Transportes:duplicate", "Transportes.duplicate");
$router->post("/edit", "Transportes:edit", "Transportes.edit");
$router->post("/liberacao", "Transportes:liberacao", "Transportes.liberacao");
$router->post("/editGlobal/{id}", "Transportes:editGlobal", "Transportes.editGlobal");

/*
* Motoristas
*/
$router->group("motoristas");
$router->get("/cadastrar", "Motoristas:cadastrar", "Motoristas.cadastrar");
$router->get("/editMotorista/{id}", "Motoristas:editMotorista", "Motoristas.editMotorista");
$router->get("/visualizar/{id}", "Motoristas:visualizar", "Motoristas.visualizar");

$router->post("/new", "Motoristas:new", "Motoristas.new");
$router->post("/edit/{id}", "Motoristas:edit", "Motoristas.edit");
$router->post("/accept", "Motoristas:accept", "Motoristas.accept");
$router->post("/delete", "Motoristas:delete", "Motoristas.delete");

/*
* Ordem de Coleta
*/
$router->group("coleta");
$router->get("/cadastrar/{transporte}", "Coletas:cadastrar", "Coletas.cadastrar");
$router->get("/editarOC/{id}", "Coletas:editarOC", "Coletas.editarOC");
$router->get("/visualizar/{id}", "Coletas:visualizar", "Coletas.visualizar");
$router->get("/download/{id}", "Coletas:download", "Coletas.download");

$router->post("/new", "Coletas:new", "Coletas.new");
$router->post("/edit/{id}", "Coletas:edit", "Coletas.edit");
$router->post("/delete", "Coletas:delete", "Coletas.delete");
$router->post("/mail/{id}", "Coletas:mail", "Coletas.mail");
$router->post("/message/{id}", "Coletas:message", "Coletas.message");
$router->post("/phone/{id}", "Coletas:phone", "Coletas.phone");
$router->post("/phone/send", "Coletas:phoneSend", "Coletas.phoneSend");

/*
* Fiscal
*/
$router->group("fiscal");
$router->get("/adicionarDocumentos/{id}", "Fiscal:addD", "Fiscal.addD");
$router->get("/visualizar/{id}/{type}", "Fiscal:visualizar", "Fiscal.visualizar");
$router->post("/getmail/{id}", "Fiscal:getMail", "Fiscal.getmail");
$router->post("/emailsend/{id}", "Fiscal:emailSend", "Fiscal.emailsend");

$router->post("/add/{id}", "Fiscal:add", "Fiscal.add");
$router->post("/delete/{id}", "Fiscal:delete", "Fiscal.delete");
$router->post("/email/{id}", "Fiscal:email", "Fiscal.email");
$router->post("/deletedoc/{id}/{type}/{base}", "Fiscal:deletedoc", "Fiscal.deletedoc");

/*
* Financeiro
*/
$router->group("financeiro");
$router->get("/adicionarComprovantes/{id}", "Financeiro:addC", "Financeiro.addC");
$router->get("/adicionarBoletos/{id}", "Financeiro:addB", "Financeiro.addB");
$router->post("/email/send", "Financeiro:emailSend", "Financeiro.emailsend");

$router->post("/addC/{id}", "Financeiro:add", "Financeiro.add");
$router->post("/addB/{id}", "Financeiro:addBol", "Financeiro.addBol");
$router->post("/deleteC/{id}", "Financeiro:delete", "Financeiro.delete");
$router->post("/deleteB/{id}", "Financeiro:deleteBol", "Financeiro.deleteBol");


/*
* Mailer
*/
$router->group("mailer");
$router->get("/{type}/{id}", "Mailer:email", "Mailer.email");

$router->post("/send", "Mailer:send", "Mailer.send");

/*
* Error
*/
$router->group("oops");
$router->get("/{errcode}", "Main:error", "Main.error");

$router->dispatch();

if($router->error()) {
    $router->redirect( "/oops/{$router->error()}" );
}