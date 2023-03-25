<?php

namespace HackbartPR\Seeds;

use Faker\Factory;
use HackbartPR\Entity\Video;

final class VideoSeed
{
    static function create(): Video
    {   
        $faker = Factory::create();
        return new Video(null, $faker->unique()->sentence(2), $faker->paragraph(2), $faker->unique()->url());
    }
}
