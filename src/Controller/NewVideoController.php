<?php

namespace HackbartPR\Controller;

use Nyholm\Psr7\Response;
use HackbartPR\Entity\Video;
use HackbartPR\Entity\Category;
use HackbartPR\Entity\Controller;
use HackbartPR\Traits\Validations;
use Psr\Http\Message\ResponseInterface;
use HackbartPR\Repository\VideoRepository;
use Psr\Http\Message\ServerRequestInterface;
use HackbartPR\Repository\CategoryRepository;

class NewVideoController extends Controller
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

        [$title, $description, $url, $category] = $validate;

        $video = $isUrlExists = $this->repository->showByUrl($url);
        if (!is_bool($isUrlExists)) {
            return new Response(400, ['Content-Type' => 'application/json'] , json_encode(['error' => 'URL already exists.']));
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
        
        $video = new Video(null, $title, $description, $url, $category);
        $isSaved = $this->repository->save($video); 

        if (!$isSaved) {
            return new Response(400);
        }
        
        $saved = $this->repository->showByUrl($url);
        $body = json_encode(['contents'=>$saved]);
        return new Response(201, ['Content-Type' => 'application/json'], $body);
    }

    private function validate(ServerRequestInterface $request): array|bool
    {
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

        return [$title, $description, $url, $category];
    }
}