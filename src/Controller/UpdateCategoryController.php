<?php

namespace HackbartPR\Controller;

use Nyholm\Psr7\Response;
use HackbartPR\Entity\Category;
use HackbartPR\Entity\Controller;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use HackbartPR\Repository\CategoryRepository;

class UpdateCategoryController extends Controller
{
    public function __construct(
        private CategoryRepository $repository
    ){}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $validate = $this->validate($request); 

        if (!$validate) {
            return new Response(400, ['Content-Type' => 'application/json'] , json_encode(['error' => 'Category format is not allowed.']));
        }

        [$id, $title, $color] = $validate;
        
        $category = $this->repository->show($id);
        if (is_bool($category)) {
            return new Response(400, ['Content-Type' => 'application/json'] , json_encode(['error' => 'Category not found.']));
        }

        $isExist = $this->repository->showByColor($color);
        if (!is_bool($isExist) && $isExist['id'] !== $id) {
            return new Response(400, ['Content-Type' => 'application/json'] , json_encode(['error' => 'Color already exists.']));
        }

        $category = new Category($id, $title, $color);
        $isUpdated = $this->repository->save($category);
        
        if (!$isUpdated) {
            return new Response(400);
        }

        $body = json_encode(['contents'=>$category]); 
        return new Response(200, ['Content-Type' => 'application/json'], $body);
    }

    private function validate(ServerRequestInterface $request): array|bool
    {
        $uri = $request->getUri()->getPath();
        $id = $this->getQueryParam($uri);
        $id = filter_var($id, FILTER_VALIDATE_INT);

        $body = $request->getBody()->getContents();
        $body = json_decode($body, true);

        if (!isset($body['title']) || !isset($body['color'])) {
            return false;
        }

        $title = filter_var($body['title'], FILTER_DEFAULT);
        $color = filter_var($body['color'], FILTER_DEFAULT);

        if (empty($title) || empty($color)) {
            return false;
        }

        if (!preg_match('/^#[a-f0-9]{6}$/i', $color)) {
            return false;
        }

        return [$id, $title, $color];
    }
}