<?php

namespace App\Serializers\DTO;

use App\DTO\Schedule\ScheduleCreationDTO;
use App\Serializers\BaseSerializer;

class ScheduleCreationDtoSerializer extends BaseSerializer {

    public function __construct()
    {
        parent::__construct();
        $this->classEntity = ScheduleCreationDTO::class;
    }

}