<?php

namespace HackbartPR\Tests\Traits;

use Dotenv\Dotenv;
use HackbartPR\Repository\VideoRepository;

trait Mock 
{
    private function getVideoRepository(): VideoRepository
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        return new VideoRepository(\HackbartPR\Config\ConnectionCreator::create());
    }        
}