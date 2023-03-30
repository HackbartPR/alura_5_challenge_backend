<?php

namespace HackbartPR\Controller;

use Nyholm\Psr7\Response;
use HackbartPR\Entity\Category;
use HackbartPR\Entity\Controller;
use HackbartPR\Traits\Validations;
use Psr\Http\Message\ResponseInterface;
use HackbartPR\Repository\CategoryRepository;
use Psr\Http\Message\ServerRequestInterface;

class NewCategoryController extends Controller
{
    use Validations;
    
    public function __construct(
        private CategoryRepository $repository
    ){}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $validate = $this->validate($request); 

        if (!$validate) {
            return new Response(400, ['Content-Type' => 'application/json'] , json_encode(['error' => 'Category format is not allowed.']));
        }

        [$title, $color] = $validate;

        $isExist = $this->repository->showByColor($color);
        if (!is_bool($isExist)) {
            return new Response(400, ['Content-Type' => 'application/json'] , json_encode(['error' => 'Color already exists.']));
        }

        $category = new Category(null, $title, $color);
        $isSaved = $this->repository->save($category); 

        if (!$isSaved) {
            return new Response(400);
        }
        
        $saved = $this->repository->showByColor($color);
        $body = json_encode(['contents'=>$saved]);
        return new Response(201, ['Content-Type' => 'application/json'], $body); 
    }

    private function validate(ServerRequestInterface $request): array|bool
    {
        $body = $request->getBody()->getContents();
        $body = json_decode($body, true);
        
        if (!isset($body['title']) || !isset($body['color'])) {
            return false;
        }
        
        [$title, $color] = $this->categoryFilterValidation($body, false);
        
        if (empty($title) || empty($color)) {
            return false;
        }

        if (!preg_match('/^#[a-f0-9]{6}$/i', $color)) {
            return false;
        }

        return [$title, $color];
    }
}