<?php

require_once __DIR__ . '/../vendor/autoload.php';

//Identificação da requisição
$action = $_SERVER['PATH_INFO'] ?? '/';
$method = $_SERVER['REQUEST_METHOD'];

//Container de Dependencias
$diContainer = require_once __DIR__ . '/../config/dependencies.php';

//Roteamento
$router = require_once __DIR__ . '/../config/routes.php';

//Criando Controller
$controller = null;
if (isset($router["$method|$action"])) {
    $class = $router["$method|$action"];
    $controller = $diContainer->get($class);
} else {
    // $controller = new 404 controller
}
