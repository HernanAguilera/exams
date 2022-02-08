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
        foreach (static::getRelations() as $relation) {
            // $class => $property_name
            // dd($data);
            if (array_key_exists($relation['field'], $data) && (is_integer($data[$relation['field']]) || is_array($data[$relation['field']]) )){
                // dd($relation['type']);
                switch ($relation['type']) {
                    case 'ManyToOne':
                        $relations[$relation['field']] = $doctrine->getRepository($relation['class'])->find($data[$relation['field']]);
                        unset($data[$relation['field']]);
                        break;
                    case 'OneToMany':
                    case 'ManyToMany':
                        $relations[$relation['field']] = [];
                        if (!is_array($data[$relation['field']]))
                            continue;
                        foreach($data[$relation['field']] as $field) {
                            $relations[$relation['field']][] = $doctrine->getRepository($relation['class'])->find($field);
                        }
                        unset($data[$relation['field']]);
                        break;
                }
            }
        }
        return [$relations, $data];
    }

    public function saveRelations($object, $relations) {
        $this->completeFields($object);
        foreach ($relations as $property_name => $related) {
            if (is_null($related))
                continue;
            if (is_array($related)){
                if (count($related) == 0)
                    continue;
                $singular = explode('\\',get_class($related[0]));
                $singular = strtolower($singular[count($singular) - 1]);

                foreach($object->{'get'.ucfirst($property_name)}() as $related_obj){
                    if(!in_array($related_obj->getId(), array_map(function($r){ return $r->getId(); }, $related))){
                        $methodName = 'remove'. ucfirst($singular);
                        $object->{$methodName}($related_obj);
                    }
                }
                
                foreach($related as $related_obj) {
                    if (!in_array($related_obj, $object->{'get'.ucfirst($property_name)}()->toArray())){
                        $methodName = 'add'.ucfirst($singular);
                        $object->{$methodName}($related_obj);
                    }
                }
            } else {
                $methodName = 'set'.ucfirst($property_name);
                $object->{$methodName}($related);
            }
        }
    }

    public function updateRelations($object, $relations) {
        $this->saveRelations($object, $relations);
    }
}
