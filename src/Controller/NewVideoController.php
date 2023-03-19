<?php

namespace HackbartPR\Controller;

use HackbartPR\Repository\VideoRepository;
use Psr\Http\Message\ServerRequestInterface;

class NewVideoController
{
    public function __construct(
        private VideoRepository $repository
    ){}

    public function handle(ServerRequestInterface $request)
    {
        if (!$this->validate($request)) {
            
        }
    }

    private function validate(ServerRequestInterface $request): bool
    {
        return true;
    }
}