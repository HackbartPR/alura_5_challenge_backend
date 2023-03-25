<?php

namespace HackbartPR\Controller;

use HackbartPR\Entity\Controller;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use HackbartPR\Repository\VideoRepository;
use Psr\Http\Message\ServerRequestInterface;

class DeleteVideoController extends Controller
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

        [$id] = $validate;
        $video = $this->repository->show($id);
        
        if (empty($video)) {
            return new Response(400, ['Content-Type' => 'application/json'] , json_encode(['error' => 'User not found.']));
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