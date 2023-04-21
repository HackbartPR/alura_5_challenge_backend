<?php

namespace HackbartPR\Middleware;

use Exception;
use Firebase\JWT\Key;
use Firebase\JWT\JWT;
use GuzzleHttp\Psr7\Response;
use HackbartPR\Entity\Controller;
use HackbartPR\Repository\UserRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AuthMiddleware
{   
    public function __construct(        
        private UserRepository $repository
    ){}
    
    public function handle(ServerRequestInterface $request, Controller $next): ResponseInterface
    {   
        if (!($next instanceof \HackbartPR\Controller\AuthController)) {
            $headers = $request->getHeaders();
            
            if (!isset($headers['Authorization'])) {
                return new Response(401, ['Content-Type' => 'application/json'] , json_encode(['error' => 'Access Denied.']));
            }

            if (count($headers['Authorization']) != 1) {
                return new Response(401, ['Content-Type' => 'application/json'] , json_encode(['error' => 'Access Denied.']));
            }

            $token = str_replace('Bearer ','', $headers['Authorization'][0]);

            try{
                $decoded = JWT::decode($token, new Key($_ENV['JWT_KEY'], $_ENV['JWT_ALGORITHM']));
            } catch(Exception $e) {
                return new Response(401, ['Content-Type' => 'application/json'] , json_encode(['error' => 'Access Denied.']));
            }
            
            if (!$this->repository->isEmailRegistered($decoded->email)) {
                return new Response(401, ['Content-Type' => 'application/json'] , json_encode(['error' => 'Access Denied.']));
            }
        }

        return $next->handle($request);
    }
}