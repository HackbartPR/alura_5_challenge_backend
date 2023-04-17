<?php

namespace HackbartPR\Controller;

use Nyholm\Psr7\Response;
use HackbartPR\Entity\Controller;
use HackbartPR\Repository\CategoryRepository;
use Psr\Http\Message\ResponseInterface;
use HackbartPR\Repository\VideoRepository;
use Psr\Http\Message\ServerRequestInterface;

class ShowVideosByCategoryController extends Controller
{
    public function __construct(
        private VideoRepository $repository,
        private CategoryRepository $categoryRepository
    ){}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        [$id] = $this->validate($request); 

        if (is_bool($id)) {
            return new Response(404, ['Content-Type' => 'application/json'] , json_encode(['error' => 'Category not found.']));
        }

        $video = $this->repository->showVideosByCategory($id);
        $body = json_encode(['contents'=>$video]);        
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