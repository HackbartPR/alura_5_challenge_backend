<?php

return [
    [
        array(
            'method'       => 'GET', 
            'path_pattern' => '/^\/videos$/',
            'controller'   => \HackbartPR\Controller\AllVideoController::class
        ),
        array(
            'method'       => 'GET',
            'path_pattern' => '/^\/videos\/(?P<id>\d+)$/',
            'controller'   => \HackbartPR\Controller\ShowVideoController::class
        ),
        array(
            'method'       => 'POST', 
            'path_pattern' => '/^\/videos$/',
            'controller'   => \HackbartPR\Controller\NewVideoController::class
        )
    ],
    \HackbartPR\Controller\NotFoundVideoController::class
];