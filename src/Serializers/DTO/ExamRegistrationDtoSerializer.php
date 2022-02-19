<?php

namespace App\Serializers\DTO;

use App\DTO\Exam\ExamRegistrationDTO;
use App\Serializers\BaseSerializer;

class ExamRegistrationDtoSerializer extends BaseSerializer {

    public function __construct()
    {
        parent::__construct();
        $this->classEntity = ExamRegistrationDTO::class;
    }

}