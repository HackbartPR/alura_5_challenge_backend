<?php

namespace HackbartPR\Controller;

use Nyholm\Psr7\Response;
use HackbartPR\Entity\Video;
use HackbartPR\Entity\Controller;
use Psr\Http\Message\ResponseInterface;
use HackbartPR\Repository\VideoRepository;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;

class UpdateVideoController extends Controller
{
    public function __construct(
        private VideoRepository $repository
    ){}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $validate = $this->validate($request); 

        if (!$validate) {
            return new Response(400);
        }

        [$id, $title, $description, $url] = $validate;
        $video = new Video($id, $title, $description, $url);
        $isUpdated = $this->repository->save($video);
        
        if (!$isUpdated) {
            return new Response(400);
        }

        $body = json_encode(['contents'=>$video]); 
        return new Response(201, ['Content-Type' => 'application/json'], $body);
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

        $title = filter_var($body['title'], FILTER_DEFAULT);
        $description = filter_var($body['description'], FILTER_DEFAULT);
        $url = filter_var($body['url'], FILTER_VALIDATE_URL);

        if (empty($title) || empty($description) || empty($url)) {
            return false;
        }

        return [$id, $title, $description, $url];
    }
}