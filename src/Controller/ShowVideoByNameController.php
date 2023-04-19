<?php

namespace HackbartPR\Controller;

use Nyholm\Psr7\Response;
use HackbartPR\Entity\Controller;
use HackbartPR\Repository\CategoryRepository;
use Psr\Http\Message\ResponseInterface;
use HackbartPR\Repository\VideoRepository;
use Psr\Http\Message\ServerRequestInterface;

class ShowVideoByNameController extends Controller
{
    public function __construct(
        private VideoRepository $repository
    ){}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {   
        $validate = $this->validate($request); 
        
        if (is_bool($validate)) {
            return new Response(404, ['Content-Type' => 'application/json'] , json_encode(['error' => 'Video not found.']));
        }

        [$param] = $validate; 

        $video = $this->repository->showVideoByName($param);
        $body = json_encode(['contents'=>$video]);        
        return new Response(200, ['Content-Type' => 'application/json'], $body);
    }

    private function validate(ServerRequestInterface $request): array|bool
    {
        $param = $request->getQueryParams();

        if (is_null($param['search'])) {
            return false;
        }

        $param = filter_var($param['search'], FILTER_DEFAULT);

        if (empty($param)) {
            return false;
        }

        return [$param];
    }
}