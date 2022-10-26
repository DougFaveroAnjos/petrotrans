<?php
date_default_timezone_set("America/Sao_Paulo");
setlocale(LC_ALL, 'pt_BR');

const URL_BASE = "http://localhost/petrotrans";

const DATA_LAYER_CONFIG = [
    "driver" => "mysql",
    "host" => "localhost",
    "port" => "3306",
    "dbname" => "petrotrans",
    "username" => "root",
    "passwd" => "",
    "options" => [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_CASE => PDO::CASE_NATURAL
    ]
];


/**
 * VIEW
 */
define("CONF_VIEW_PATH", __DIR__ . "/../../shared/views");
define("CONF_VIEW_EXT", "php");

/**
 * MAIL
 */
define("CONF_MAIL_HOST", "smtp.petrotrans.com.br");
define("CONF_MAIL_PORT", "587");
define("CONF_MAIL_USER", "comercial=petrotrans.com.br");
define("CONF_MAIL_PASS", "171163@rp");
define("CONF_MAIL_SENDER", ["name" => "Petrotrans", "address" => "comercial@petrotrans.com.br"]);
define("CONF_MAIL_SUPPORT", "comercial@petrotrans.com.br");
define("CONF_MAIL_OPTION_LANG", "br");
define("CONF_MAIL_OPTION_HTML", true);
define("CONF_MAIL_OPTION_SMTP_SECURE", false);
define("CONF_MAIL_OPTION_SMTP_AUTOTLS", false);
define("CONF_MAIL_OPTION_SMTP_AUTH", true);
define("CONF_MAIL_OPTION_CHARSET", "UTF-8");

/**
 * LOG
 */

define("CONF_LOG_PATH", __DIR__."/../logs");

/**
 * @param string|null $uri
 * @return string
 */
function url(string $uri = null): string
{
    if($uri) {
        return URL_BASE."/{$uri}";
    }
    return URL_BASE;
}

function is_email(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function deixarNumero($string){
  return preg_replace("/[^0-9]/", "", $string);
}

function str_price(?string $price): string
{
    return number_format((!empty($price) ? $price : 0), 2, ",", ".");
}
define("CONF_WHATSAPP_PATH", "https://v5.chatpro.com.br/");
define("CONF_WHATSAPP_CODIGO", "chatpro-jc8sy7bgvv");
define("CONF_WHATSAPP_KEY", "fo82jjb08s8svkai00s8kvfhvky56y");