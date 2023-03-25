<?php

namespace HackbartPR\Tests\Traits;

use GuzzleHttp\Client;
use Nyholm\Psr7\ServerRequest;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

trait Request 
{
    private $serverUrl = 'http://localhost:8080';
    
    private function createRequest($method, $uri, $params = [], $body = null): ServerRequestInterface
    {
        $factory = new Psr17Factory();
        return new ServerRequest($method, $this->serverUrl . $uri, $params, $body);
    }

    private function sendRequest(ServerRequestInterface $request): ResponseInterface
    {
        $client = new Client();
        return $client->sendRequest($request);
    }
}