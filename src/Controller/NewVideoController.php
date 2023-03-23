<?php

namespace HackbartPR\Controller;

use Nyholm\Psr7\Response;
use HackbartPR\Entity\Video;
use Psr\Http\Message\ResponseInterface;
use HackbartPR\Repository\VideoRepository;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;

class NewVideoController implements RequestHandlerInterface
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

        [$title, $description, $url] = $validate;
        $video = new Video(null, $title, $description, $url);
        $isSaved = $this->repository->save($video); 

        if (!$isSaved) {
            return new Response(400);
        }
        
        return new Response(201, ['Content-Type' => 'application/json'], json_encode($video));
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