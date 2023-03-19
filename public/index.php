<?php

require_once __DIR__ . '/../vendor/autoload.php';

//Identificação da requisição
$action = $_SERVER['PATH_INFO'] ?? '/';
$method = $_SERVER['REQUEST_METHOD'];

//Container de Dependencias (PSR 11)
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

// Criando um objeto HTTP Request (PSR7) utilizando a fabrica de objeto (PSR 17)
$psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();
$creator = new \Nyholm\Psr7Server\ServerRequestCreator(
    $psr17Factory, // ServerRequestFactory
    $psr17Factory, // UriFactory
    $psr17Factory, // UploadedFileFactory
    $psr17Factory  // StreamFactory
);
$serverRequest = $creator->fromGlobals();

//Chamando Controller
$controller->handle($serverRequest);
