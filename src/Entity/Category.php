<?php

namespace HackbartPR\Entity;

class Category
{
    public function __construct(
        private int|null $id, 
        public string $title,
        public string $color
    ){}

    public function id(): int|null
    {
        return $this->id;
    }
}