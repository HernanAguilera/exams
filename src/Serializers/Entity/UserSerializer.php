<?php

namespace App\Serializers\Entity;

use App\Entity\User;
use App\Serializers\BaseSerializer;

class UserSerializer extends BaseSerializer {

    public function __construct()
    {
        parent::__construct();
        $this->classEntity = User::class;
    }

}