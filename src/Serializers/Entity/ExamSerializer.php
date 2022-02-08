<?php

namespace App\Serializers\Entity;

use App\Entity\Exam;
use App\Serializers\BaseSerializer;

class ExamSerializer extends BaseSerializer {

    public function __construct()
    {
        parent::__construct();
        $this->classEntity = Exam::class;
    }

}