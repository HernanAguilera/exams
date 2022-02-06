<?php

namespace App\Serializers\DTO;

use App\DTO\RegisterDTO;
use App\Serializers\BaseSerializer;

class RegisterDtoSerializer extends BaseSerializer {

    public function __construct()
    {
        parent::__construct();
        $this->classEntity = RegisterDTO::class;
    }

}