<?php

namespace HackbartPR\Controller;

use Nyholm\Psr7\Response;
use HackbartPR\Entity\Params;
use Psr\Http\Message\ResponseInterface;
use HackbartPR\Repository\VideoRepository;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;

class ShowVideoController extends Params implements RequestHandlerInterface
{
    public function __construct(
        private VideoRepository $repository
    ){}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        [$id] = $this->validate($request); 

        if (!$id) {
            return new Response(400);
        }

        $video = $this->repository->show($id);        
        return new Response(200, ['Content-Type' => 'application/json'], json_encode($video));
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