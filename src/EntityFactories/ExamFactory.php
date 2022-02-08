<?php

namespace App\EntityFactories;

use App\Entity\Exam;
use Doctrine\ORM\EntityManager;
use Faker\Factory;

class ExamFactory extends BaseEntityFactory
{
    public static function add($n=1, $options=[]) {
        $faker = Factory::create();
        $defaults = [
            'name' => function() use ($faker) { return $faker->words(rand(1, 10), true); }
        ];
        $objects = self::fillData($n, Exam::class,$defaults, $options);
        return $n > 1? $objects : $objects[0];
    }

    public static function create(EntityManager $manager, $n=1, $options = []) {
        return self::persist($manager, self::add($n, $options));
    }
}
