<?php

namespace HackbartPR\Entity;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

abstract class Controller implements RequestHandlerInterface
{   
    protected function getQueryParam(string $uri): string|bool
    {
        return filter_var($uri, FILTER_SANITIZE_NUMBER_INT);
    }    
}
