<?php
/**
 * Created by PhpStorm.
 * User: win
 * Date: 11/10/2022
 * Time: 12:25
 */

namespace Source\Support;

use DateTimeZone;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Source\Models\Users;

class Log extends Logger
{
    private $dados = [];
    private $path;

    public function __construct($name, $handlers = [], $processors = [], $timezone = null)
    {
        parent::__construct($name, $handlers, $processors, $timezone);

        $this->path = $name;

        $this->dados = ["IP"=>$_SERVER['REMOTE_ADDR']];
        $usuario = (new Users())->findById($_SESSION['id']);

        if(!empty($_SESSION['id'])){
            $this->dados["id"] = $usuario->id;
            $this->dados["email"] = $usuario->email;
        }
    }

    public function archive()
    {
        $path = CONF_LOG_PATH."/{$this->path}_".date("Y-m-d").".log";
        $this->pushHandler(new StreamHandler($path, Logger::INFO));
        return $this;
    }

    public function info($message, array $context = []): void
    {
        if(!empty($context)){
            array_push($this->dados, $context);
        }

        parent::info($message, $this->dados);
    }
}