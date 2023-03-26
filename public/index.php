<?php

require_once __DIR__ . '/../vendor/autoload.php';

//Mostrar mensagens de Debug
/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

//Container de Dependencias (PSR 11)
$diContainer = require_once __DIR__ . '/../config/dependencies.php';

//Roteamento
$routes = require_once __DIR__ . '/../config/routes.php';

//Criando Controller
$class = \HackbartPR\Config\Router::route($routes);
$controller = $diContainer->get($class);

// Criando um objeto HTTP Request (PSR7) utilizando a fabrica de objeto (PSR 17)
$psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();
$creator = new \Nyholm\Psr7Server\ServerRequestCreator(
    $psr17Factory, // ServerRequestFactory
    $psr17Factory, // UriFactory
    $psr17Factory, // UploadedFileFactory
    $psr17Factory  // StreamFactory
);
$serverRequest = $creator->fromGlobals();

//Recebendo Response do Controller
$responseController = $controller->handle($serverRequest);

//Enviando Response para o client usando Stream
$responseBody = $psr17Factory->createStream($responseController->getBody());

$response = $psr17Factory->createResponse($responseController->getStatusCode())->withBody($responseBody);

foreach ($responseController->getHeaders() as $header => $value) {
    $response = $response->withHeader($header, $value);
}

(new \Laminas\HttpHandlerRunner\Emitter\SapiEmitter())->emit($response);