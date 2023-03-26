<?php

namespace HackbartPR\Controller;

use HackbartPR\Entity\Controller;
use Psr\Http\Message\ResponseInterface;
use HackbartPR\Repository\CategoryRepository;
use Psr\Http\Message\ServerRequestInterface;

class NewCategoryController extends Controller
{
    public function __construct(
        private CategoryRepository $repository
    ){}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        
    }

    private function validate(ServerRequestInterface $request): array|bool
    {
        
    }
}