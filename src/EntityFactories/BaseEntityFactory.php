<?php

namespace App\EntityFactories;

use Doctrine\ORM\EntityManager;

class BaseEntityFactory
{
    protected static function fillData($n, $class, $defaults, $options) {
        $contents = [];
        for ($i=0; $i < $n; $i++) {
            $data = [];
            $content = new $class;
            foreach($defaults as $key => $default){
                $data[$key] = array_key_exists($key, $options)
                                ? (is_callable($options[$key])
                                    ? $options[$key]()
                                    : $options[$key]
                                )
                                : $default() ;
                $content->{'set'. self::snakeCaseToCamelCase($key)}($data[$key]);
            }

            $contents[] = $content;
        }
        return $contents;
    }

    public static function snakeCaseToCamelCase(String $string) {
        $words = explode('_', $string);
        $up_words = array_map(function($word) {
            return ucfirst($word);
        }, $words);
        return implode($up_words);
    }

    public static function persist(EntityManager $manager, $data) {
        if (is_array($data)){
            foreach($data as $entity){
                $manager->persist($entity);
            }
        } else {
            $manager->persist($data);
        }
        $manager->flush();
        return $data;
    }
}
