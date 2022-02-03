<?php

namespace App\Traits;

use App\Repository\MetadataInterface;

/**
 * 
 */
trait EntityRepositoryTrait
{
    public function checkRelations($data, $doctrine) {
        $relations = [];
        foreach (static::getRelations() as $class => $property_name) {
            if (array_key_exists($property_name, $data) && is_integer($data[$property_name])){
                $relations[$property_name] = $doctrine->getRepository($class)->find($data[$property_name]);
                unset($data[$property_name]);
            }
        }
        return [$relations, $data];
    }

    public function saveRelations($object, $relations) {
        $this->completeFields($object);
        foreach ($relations as $property_name => $related_obj) {
            $methodName = 'set'.ucfirst($property_name);
            if (!is_null($related_obj))
                $object->{$methodName}($related_obj);
        }
    }

    public function updateRelations($object, $relations) {
        $this->saveRelations($object, $relations);
    }
}
