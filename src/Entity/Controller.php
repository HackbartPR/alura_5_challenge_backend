<?php

namespace HackbartPR\Entity;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

abstract class Controller implements RequestHandlerInterface
{   
    protected function getQueryParam(string $uri): string|bool
    {
        $uriArray = explode('/', $uri);
        $uriArray = array_filter($uriArray, 'strlen');

        if (count($uriArray) > 2) {
            return false;
        }

        return end($uriArray);
    }
}
