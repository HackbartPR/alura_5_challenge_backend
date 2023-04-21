<?php

namespace HackbartPR\Traits;

use HackbartPR\Entity\Category;

trait Validations
{
    private function videoFilterValidation(array $body, bool $hasId): array
    {
        $title = filter_var($body['title'], FILTER_DEFAULT);
        $description = filter_var($body['description'], FILTER_DEFAULT);
        $url = filter_var($body['url'], FILTER_VALIDATE_URL);

        if ($hasId) {
            $id = filter_var($body['id'], FILTER_VALIDATE_INT);
            return [$id, $title, $description, $url];    
        }
        
        return [$title, $description, $url];
    }

    private function categoryFilterValidation(array $body, bool $hasId): array
    {
        if (isset($body['title']) || !isset($body['color'])) {
            $title = filter_var($body['title'], FILTER_DEFAULT);
            $color = filter_var($body['color'], FILTER_DEFAULT);
        }        
        
        if ($hasId) {
            $id = filter_var($body['id'], FILTER_VALIDATE_INT);
            return [$id, $title, $color];    
        }

        return [$title, $color];
    }

    private function userValidation(array $body): array
    {
        $email = filter_var($body['email'], FILTER_VALIDATE_EMAIL);
        $password = filter_var($body['password'], FILTER_DEFAULT);

        return [$email, $password];
    }
}