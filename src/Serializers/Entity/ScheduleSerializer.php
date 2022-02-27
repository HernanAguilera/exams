<?php

namespace App\Serializers\Entity;

use App\Entity\Schedule;
use App\Serializers\BaseSerializer;

class ScheduleSerializer extends BaseSerializer {

    protected $fields = [
        'id',
        'exam' => ['id', 'name'],
        'date'
    ];

    public function __construct()
    {
        parent::__construct();
        $this->classEntity = Schedule::class;
    }

     /**
     * Get the value of fields
     */ 
    public function getFields()
    {
        return $this->fields;
    }

}