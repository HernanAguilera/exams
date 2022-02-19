<?php

namespace App\EntityFactories;

use App\Entity\Test;
use Doctrine\ORM\EntityManager;
use Faker\Factory;

class TestFactory extends BaseEntityFactory
{
    public static function add($n=1, $options=[]) {
        $faker = Factory::create();
        $defaults = [
            'status' => function() { return Test::RESERVED; },
            'date' => function() use ($faker) { return $faker->dateTimeBetween('+1 week', '+4 weeks'); },
            'attended' => function() { return false; }
        ];
        $objects = self::fillData($n, Test::class,$defaults, $options);
        return $n > 1? $objects : $objects[0];
    }

    public static function create(EntityManager $manager, $n=1, $options = []) {
        return self::persist($manager, self::add($n, $options));
    }
}
