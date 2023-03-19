<?php

namespace HackbartPR\Controller;

use Nyholm\Psr7\Response;
use HackbartPR\Repository\VideoRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;

class NewVideoController implements RequestHandlerInterface
{
    public function __construct(
        private VideoRepository $repository
    ){}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        

        return new Response(200, ['Content-Type' => 'application/json']);
    }
}