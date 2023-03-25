<?php

namespace HackbartPR\Controller;

use Nyholm\Psr7\Response;
use HackbartPR\Entity\Controller;
use Psr\Http\Message\ResponseInterface;
use HackbartPR\Repository\VideoRepository;
use Psr\Http\Message\ServerRequestInterface;

class AllVideoController extends Controller
{
    public function __construct(
        private VideoRepository $repository
    ){}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $videoList = $this->repository->all();
        $body = json_encode(['contents'=>$videoList]);

        return new Response(200, ['Content-Type' => 'application/json'], $body);
    }
}