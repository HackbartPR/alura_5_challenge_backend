<?php

namespace HackbartPR\Entity;

abstract class Params
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
