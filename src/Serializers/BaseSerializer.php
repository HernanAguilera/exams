<?php

namespace App\Serializers;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class BaseSerializer implements SerializerInterface{

    protected $defaultContext;
    protected $encoders;
    protected $normalizers;
    protected $serializer;
    protected $classEntity;

    public function __construct()
    {
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getId();
            },
        ];
        $this->setDefaultContext($defaultContext);
        $encoders = [new JsonEncoder()];
        $this->setEncoders($encoders);
        $normalizers = [new ObjectNormalizer(null, null, null, null, null, null, $defaultContext)];
        $this->setNormalizers($normalizers);
        $this->serializer = $this->getSerializer();
    }

    public function setDefaultContext($defaultContext = []) {
        $this->defaultContext = $defaultContext;
    }

    public function getDefaultContext() {
        return $this->defaultContext;
    }

    public function setEncoders($encoders) {
        $this->encoders = $encoders;
    }

    public function getEncoders() {
        return $this->encoders;
    }

    public function setNormalizers($normalizers){
        $this->normalizers = $normalizers;
    }
    
    public function getNormalizers() {
        return $this->normalizers;
    }

    protected function getSerializer($forced = false) {
        if (is_null($this->serializer) || $forced)
            return new Serializer($this->getNormalizers(), $this->getEncoders());
        return $this->serializer;
    }

    public function getClassEntity() {
        return $this->classEntity;
    }

    public function deserialize($data, $context = []) {
        return $this->getSerializer()->deserialize(
            $data,
            $this->classEntity,
            'json',
            $context
        );
    }

    public function serialize($data) {
        return $this->getSerializer()->serialize($data, 'json');
    }

    public function normalize($entity, $fields = []): array {
        if (count($fields) === 0)
            return $this->getNormalizers()[0]->normalize($entity);
        return $this->getNormalizers()[0]->normalize($entity, null, [ AbstractNormalizer::ATTRIBUTES => $fields ]);
    }
}