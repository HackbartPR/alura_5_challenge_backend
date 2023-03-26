<?php

namespace HackbartPR\Controller;

use Nyholm\Psr7\Response;
use HackbartPR\Entity\Controller;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use HackbartPR\Repository\CategoryRepository;

class AllCategoryController extends Controller
{
    public function __construct(
        private CategoryRepository $repository
    ){}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $categList = $this->repository->all();
        $body = json_encode(['contents'=>$categList]);

        return new Response(200, ['Content-Type' => 'application/json'], $body);
    }
}