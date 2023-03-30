<?php

namespace HackbartPR\Controller;

use Nyholm\Psr7\Response;
use HackbartPR\Entity\Video;
use HackbartPR\Entity\Category;
use HackbartPR\Entity\Controller;
use HackbartPR\Traits\Validations;
use Psr\Http\Message\ResponseInterface;
use HackbartPR\Repository\VideoRepository;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use HackbartPR\Repository\CategoryRepository;

class UpdateVideoController extends Controller
{
    use Validations;

    public function __construct(
        private VideoRepository $repository,
        private CategoryRepository $categoryRepository
    ){}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $validate = $this->validate($request); 

        if (!$validate) {
            return new Response(400, ['Content-Type' => 'application/json'] , json_encode(['error' => 'Video format is not allowed.']));
        }

        [$id, $title, $description, $url, $category] = $validate;
        
        $video = $this->repository->show($id);
        if (is_bool($video)) {
            return new Response(400, ['Content-Type' => 'application/json'] , json_encode(['error' => 'Video not found.']));
        }

        if (is_null($category)) {
            $ctg = $this->categoryRepository->show(1);
            $category = new Category($ctg['id'], $ctg['title'], $ctg['color']); 

        } else {
            $ctg = $this->categoryRepository->show($category[0]);
            if (!$ctg) {
                return new Response(404, ['Content-Type' => 'application/json'] , json_encode(['error' => 'Category not found.']));
            }

            $category = new Category($ctg['id'], $ctg['title'], $ctg['color']); 
        }

        $isExist = $this->repository->showByUrl($url);
        if (!is_bool($isExist) && $isExist['id'] !== $id) {
            return new Response(400, ['Content-Type' => 'application/json'] , json_encode(['error' => 'URL already exists.']));
        }

        $video = new Video($id, $title, $description, $url, $category);
        $isUpdated = $this->repository->save($video);
        
        if (!$isUpdated) {
            return new Response(400);
        }

        $body = json_encode(['contents'=>$video]); 
        return new Response(200, ['Content-Type' => 'application/json'], $body);
    }

    private function validate(ServerRequestInterface $request): array|bool
    {
        $uri = $request->getUri()->getPath();
        $id = $this->getQueryParam($uri);
        $id = filter_var($id, FILTER_VALIDATE_INT);

        $body = $request->getBody()->getContents();
        $body = json_decode($body, true);

        if (!isset($body['title']) || !isset($body['description']) || !isset($body['url']) ) {
            return false;
        }

        [$title, $description, $url] = $this->videoFilterValidation($body, false);

        if (empty($title) || empty($description) || empty($url)) {
            return false;
        }

        $category = null;
        if (isset($body['category'])) {
            $category = ['id' => $ctgId,'title' => $ctgTitle,'color' => $ctgColor] = $this->categoryFilterValidation($body['category'], true);            
        }

        return [$id, $title, $description, $url, $category];
    }
}