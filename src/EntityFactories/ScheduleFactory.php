<?php

namespace App\EntityFactories;

use App\Entity\Schedule;
use Doctrine\ORM\EntityManager;
use Faker\Factory;

class ScheduleFactory extends BaseEntityFactory
{
    public static function add($n=1, $options=[]) {
        $faker = Factory::create();
        $defaults = [
            'date' => function() use ($faker) { return $faker->dateTimeBetween('+1 week', '+4 weeks'); }
        ];
        $objects = self::fillData($n, Schedule::class, $defaults, $options);
        return $n > 1? $objects : $objects[0];
    }

    public static function create(EntityManager $manager, $n=1, $options = []) {
        return self::persist($manager, self::add($n, $options));
    }
}
