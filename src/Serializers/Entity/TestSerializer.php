<?php

namespace App\Serializers\Entity;

use App\Entity\Test;
use App\Serializers\BaseSerializer;

class TestSerializer extends BaseSerializer {

    protected $fields = [
        'id',
        'exam' => ['id', 'name'],
        'user' => ['id', 'email'],
        'date'
    ];

    public function __construct()
    {
        parent::__construct();
        $this->classEntity = Test::class;
    }

     /**
     * Get the value of fields
     */ 
    public function getFields()
    {
        return $this->fields;
    }

}