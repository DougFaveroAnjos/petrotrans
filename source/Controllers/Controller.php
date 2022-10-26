<?php


namespace Source\Controllers;


use CoffeeCode\Router\Router;
use League\Plates\Engine;

abstract class Controller
{

    /** @var Engine */
    protected $view;
    /** @var Router */
    protected $router;

public function __construct($router, $page = null, $dir = null, $globals = [])
{

    $dir = dirname( __DIR__, 2)."/theme/pages/".$page."/";
    $this->view = new Engine($dir, "php");
    $this->router = $router;

    $this->view->addData(["router" => $this->router]);
    if($globals) {
        $this->view->addData($globals);
    }
}

public function ajaxMessage(string $message, string $type): string
{
    return json_encode(["message"=> "<div class=\"message {$type}\">{$message}</div>"]);
}

}