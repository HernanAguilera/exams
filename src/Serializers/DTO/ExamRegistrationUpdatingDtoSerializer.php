<?php

namespace App\Serializers\DTO;

use App\DTO\Exam\ExamRegistrationUpdatingDTO;
use App\Serializers\BaseSerializer;

class ExamRegistrationUpdatingDtoSerializer extends BaseSerializer {

    public function __construct()
    {
        parent::__construct();
        $this->classEntity = ExamRegistrationUpdatingDTO::class;
    }

}