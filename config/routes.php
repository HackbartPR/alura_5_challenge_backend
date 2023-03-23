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
        )
        
    ],
    \HackbartPR\Controller\NotFoundVideoController::class
];