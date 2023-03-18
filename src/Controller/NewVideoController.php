<?php

namespace HackbartPR\Controller;

use HackbartPR\Repository\VideoRepository;

class NewVideoController
{
    public function __construct(
        private VideoRepository $repository
    ){}

    public function handle()
    {
        if (!$this->validate()) {
            
        }
    }

    private function validate(): bool
    {
        return true;
    }
}