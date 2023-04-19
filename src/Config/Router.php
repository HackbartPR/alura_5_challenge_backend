<?php

namespace HackbartPR\Config;

final class Router
{   
    static public function route(array $routes) {        
        $action = $_SERVER['REQUEST_URI'] ?? '/';
        $method = $_SERVER['REQUEST_METHOD'];

        $isMatch = false;
        $controller = null;        

        foreach ($routes[0] as $route) {
            if (preg_match($route['path_pattern'], $action, $match) && in_array($method, $route['method'])) {                
                $controller = $route['controller'];
                $isMatch = true;
            }
        }

        if (!$isMatch) {
            return $routes[1]; //Controller 404;
        }

        return $controller;
    }


}