<?php

namespace HackbartPR\Entity;

class Video
{
    public function __construct(
        private int|null $id, 
        public string $title,
        public string $description,
        public string $url,
        public Category|null $category
    )
    {}

    public function id(): int|null
    {
        return $this->id;
    }
    
    public function addCategory(Category $category): void
    {
        $this->category = $category;
    }
}