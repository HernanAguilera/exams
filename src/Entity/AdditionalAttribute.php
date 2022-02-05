<?php

namespace App\Entity;

use App\Repository\AdditionalAttributeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdditionalAttributeRepository::class)
 */
class AdditionalAttribute
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $attribute;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $value;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $related_attribute;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $related_value;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAttribute(): ?string
    {
        return $this->attribute;
    }

    public function setAttribute(string $attribute): self
    {
        $this->attribute = $attribute;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getRelatedAttribute(): ?string
    {
        return $this->related_attribute;
    }

    public function setRelatedAttribute(?string $related_attribute): self
    {
        $this->related_attribute = $related_attribute;

        return $this;
    }

    public function getRelatedValue(): ?string
    {
        return $this->related_value;
    }

    public function setRelatedValue(?string $related_value): self
    {
        $this->related_value = $related_value;

        return $this;
    }
}
