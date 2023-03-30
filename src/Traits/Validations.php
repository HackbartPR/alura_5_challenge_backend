<?php

namespace HackbartPR\Traits;

use HackbartPR\Entity\Category;

trait Validations
{
    private function videoValidation(array $body): array|bool
    {
        if (!isset($body['title']) || !isset($body['description']) || !isset($body['url']) ) {
            return false;
        }

        [$title, $description, $url] = $this->videoFilterValidation($body, false);
        
        if (empty($title) || empty($description) || empty($url)) {
            return false;
        }

        $category = null;
        if (isset($body['category'])) {
            $category = ['id' => $ctgId,'title' => $ctgTitle,'color' => $ctgColor] = $this->categoryFilterValidation($body['category'], true);            
        }

        return [$title, $description, $url, $category];
    }

    /* private function categoryValidation(array $body): array|bool
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
    } */

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
}