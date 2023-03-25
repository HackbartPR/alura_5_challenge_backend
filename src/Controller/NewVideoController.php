<?php

namespace HackbartPR\Controller;

use Nyholm\Psr7\Response;
use HackbartPR\Entity\Video;
use HackbartPR\Entity\Controller;
use Psr\Http\Message\ResponseInterface;
use HackbartPR\Repository\VideoRepository;
use Psr\Http\Message\ServerRequestInterface;

class NewVideoController extends Controller
{
    public function __construct(
        private VideoRepository $repository
    ){}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $validate = $this->validate($request); 

        if (!$validate) {
            return new Response(400, ['Content-Type' => 'application/json'] , json_encode(['error' => 'Video format is not allowed.']));
        }

        [$title, $description, $url] = $validate;

        $isExist = $this->repository->showByUrl($url);

        if (!is_bool($isExist)) {
            return new Response(400, ['Content-Type' => 'application/json'] , json_encode(['error' => 'URL already exists.']));
        }

        $video = new Video(null, $title, $description, $url);
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

        $title = filter_var($body['title'], FILTER_DEFAULT);
        $description = filter_var($body['description'], FILTER_DEFAULT);
        $url = filter_var($body['url'], FILTER_VALIDATE_URL);

        if (empty($title) || empty($description) || empty($url)) {
            return false;
        }

        return [$title, $description, $url];
    }
}