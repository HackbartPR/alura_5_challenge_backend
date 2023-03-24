<?php

namespace HackbartPR\Controller;

use Nyholm\Psr7\Response;
use HackbartPR\Entity\Controller;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class NotFoundVideoController extends Controller
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new Response(404);
    }
}