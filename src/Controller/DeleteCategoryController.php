<?php

namespace HackbartPR\Controller;

use Nyholm\Psr7\Response;
use HackbartPR\Entity\Controller;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use HackbartPR\Repository\CategoryRepository;

class DeleteCategoryController extends Controller
{
    public function __construct(
        private CategoryRepository $repository
    ){}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $validate = $this->validate($request); 

        if (!$validate) {
            return new Response(400);
        }

        [$id] = $validate;
        $category = $this->repository->show($id);
        
        if (empty($category)) {
            return new Response(400, ['Content-Type' => 'application/json'] , json_encode(['error' => 'Category not found.']));
        }
        
        $isDeleted = $this->repository->delete($id);
        
        if (!$isDeleted) {
            return new Response(400);
        }

        return new Response(200);
    }

    private function validate(ServerRequestInterface $request): array|bool
    {
        $uri = $request->getUri()->getPath();
        $id = $this->getQueryParam($uri);
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if (empty($id)) {
            return false;
        }

        return [$id];
    }
}