<?php

namespace App\Serializers;

interface SerializerInterface {

    public function serialize($data);
    public function deserialize($data, $context);
}