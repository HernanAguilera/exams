<?php

namespace App\EntityFactories;

use App\Entity\Company;
use Doctrine\ORM\EntityManager;
use Faker\Factory;

class CompanyFactory extends BaseEntityFactory
{
    public static function add($n=1, $options=[]) {
        $faker = Factory::create();
        $defaults = [
            'commercial_name' => function() use ($faker) { return $faker->words(rand(1, 5), true); },
            'legal_name' => function() use ($faker) { return $faker->words(rand(1, 5), true); },
            'tax_id' => function() use ($faker) { return $faker->regexify("[A-Z0-9]{8,12}"); },
        ];
        $objects = self::fillData($n, Company::class,$defaults, $options);
        return $n > 1? $objects : $objects[0];
    }

    public static function create(EntityManager $manager, $n=1, $options = []) {
        return self::persist($manager, self::add($n, $options));
    }
}
