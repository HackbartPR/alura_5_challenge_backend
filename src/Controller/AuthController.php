<?php

namespace HackbartPR\Controller;

use Firebase\JWT\JWT;
use Nyholm\Psr7\Response;
use HackbartPR\Tools\Hash;
use HackbartPR\Entity\Controller;
use HackbartPR\Traits\Validations;
use Psr\Http\Message\ResponseInterface;
use HackbartPR\Repository\UserRepository;
use Psr\Http\Message\ServerRequestInterface;

class AuthController extends Controller
{
    use Validations;

    public function __construct(        
        private UserRepository $repository
    ){}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
            $validate = $this->validate($request); 

        if (!$validate) {
            return new Response(404, ['Content-Type' => 'application/json'] , json_encode(['error' => 'Email not found.']));
        }

        [$email, $password] = $validate;

        $user = $this->repository->searchByEmail($email);
        $isPasswordCorrect = Hash::passwordVerify($password, $user['password'] ?? '');

        if (is_bool($user) || !$isPasswordCorrect) {
            return new Response(401, ['Content-Type' => 'application/json'] , json_encode(['error' => 'Email or password not found.']));
        }        

        $payload = ['email' => $email];
        $key = JWT::encode($payload, $_ENV['JWT_KEY'], $_ENV['JWT_ALGORITHM']);

        $body = json_encode(['contents'=> $key]);
        return new Response(201, ['Content-Type' => 'application/json'], $body);
    }

    private function validate(ServerRequestInterface $request): array|bool
    {
        $body = $request->getBody()->getContents();
        $body = json_decode($body, true);
        
        if (!isset($body['email']) || !isset($body['password'])) {
            return false;
        }

        [$email, $password] = $this->userValidation($body);
        
        if (empty($email)) {
            return false;
        }
        
        return [$email, $password];
    }
}