<?php

namespace HackbartPR\Seeds;

use Faker\Factory;
use HackbartPR\Entity\Category;

final class CategorySeed
{
    static function create(): Category
    {   
        $faker = Factory::create();
        return new Category(null, $faker->unique()->colorName(), $faker->unique()->hexColor());
    }
}
