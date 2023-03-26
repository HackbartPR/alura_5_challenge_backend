<?php

namespace HackbartPR\Controller;

use Nyholm\Psr7\Response;
use HackbartPR\Entity\Controller;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use HackbartPR\Repository\CategoryRepository;

class ShowCategoryController extends Controller
{
    public function __construct(
        private CategoryRepository $repository
    ){}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        [$id] = $this->validate($request); 

        if (is_bool($id)) {
            return new Response(400);
        }

        $category = $this->repository->show($id);
        $body = json_encode(['contents'=>$category]);        
        return new Response(200, ['Content-Type' => 'application/json'], $body);
    }

    private function validate(ServerRequestInterface $request): array|bool
    {
        $uri = $request->getUri()->getPath();
        $id = $this->getQueryParam($uri);
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if (is_bool($id)) {
            return false;
        }

        return [$id];
    }
}