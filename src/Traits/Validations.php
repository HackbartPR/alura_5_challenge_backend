<?php

namespace HackbartPR\Traits;

trait Validations
{
    private function videoValidation(array $body): array|bool
    {
        if (!isset($body['title']) || !isset($body['description']) || !isset($body['url']) ) {
            return false;
        }

        $title = filter_var($body['title'], FILTER_DEFAULT);
        $description = filter_var($body['description'], FILTER_DEFAULT);
        $url = filter_var($body['url'], FILTER_VALIDATE_URL);

        $category = null;
        if (isset($body['category'])) {
            $category['id'] = filter_var($body['category']['id'], FILTER_VALIDATE_INT);


            [ $category['title'], $category['color'] ] = $this->categoryValidation($body['category']);             
        }

        if (empty($title) || empty($description) || empty($url)) {
            return false;
        }

        return [$title, $description, $url, $category];
    }

    private function categoryValidation(array $body): array|bool
    {
        if (!isset($body['title']) || !isset($body['color'])) {
            return false;
        }
        
        $title = filter_var($body['title'], FILTER_DEFAULT);
        $color = filter_var($body['color'], FILTER_DEFAULT);

        if (empty($title) || empty($color)) {
            return false;
        }

        if (!preg_match('/^#[a-f0-9]{6}$/i', $color)) {
            return false;
        }

        return [$title, $color];
    }
}