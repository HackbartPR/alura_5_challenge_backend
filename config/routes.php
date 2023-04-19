<?php

return [
    [
        array(
            'method'       => ['GET'], 
            'path_pattern' => '/^\/videos$/',
            'controller'   => \HackbartPR\Controller\AllVideoController::class
        ),
        array(
            'method'       => ['GET'],
            'path_pattern' => '/^\/videos\/(?P<id>\d+)$/',
            'controller'   => \HackbartPR\Controller\ShowVideoController::class
        ),
        array(
            'method'       => ['GET'],
            'path_pattern' => '/^\/videos\?search=(?P<name>[^\/]+)$/',
            'controller'   => \HackbartPR\Controller\ShowVideoByNameController::class
        ),
        array(
            'method'       => ['POST'], 
            'path_pattern' => '/^\/videos$/',
            'controller'   => \HackbartPR\Controller\NewVideoController::class
        ),
        array(
            'method'       => ['PUT', 'PATCH'], 
            'path_pattern' => '/^\/videos\/(?P<id>\d+)$/',
            'controller'   => \HackbartPR\Controller\UpdateVideoController::class
        ),
        array(
            'method'       => ['DELETE'], 
            'path_pattern' => '/^\/videos\/(?P<id>\d+)$/',
            'controller'   => \HackbartPR\Controller\DeleteVideoController::class
        ),
        array(
            'method'       => ['POST'], 
            'path_pattern' => '/^\/categorias$/',
            'controller'   => \HackbartPR\Controller\NewCategoryController::class
        ),
        array(
            'method'       => ['GET'], 
            'path_pattern' => '/^\/categorias/',
            'controller'   => \HackbartPR\Controller\AllCategoryController::class
        ),
        array(
            'method'       => ['GET'],
            'path_pattern' => '/^\/categorias\/(?P<id>\d+)$/',
            'controller'   => \HackbartPR\Controller\ShowCategoryController::class
        ),
        array(
            'method'       => ['PUT', 'PATCH'], 
            'path_pattern' => '/^\/categorias\/(?P<id>\d+)$/',
            'controller'   => \HackbartPR\Controller\UpdateCategoryController::class
        ),
        array(
            'method'       => ['DELETE'], 
            'path_pattern' => '/^\/categorias\/(?P<id>\d+)$/',
            'controller'   => \HackbartPR\Controller\DeleteCategoryController::class
        ),
        array(
            'method'       => ['GET'],
            'path_pattern' => '/^\/categorias\/(?P<id>\d+)\/videos/',
            'controller'   => \HackbartPR\Controller\ShowVideosByCategoryController::class
        ),        
    ],
    \HackbartPR\Controller\NotFoundVideoController::class
];