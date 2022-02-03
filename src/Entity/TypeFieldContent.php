<?php

namespace App\Entity;

use App\Repository\TypeFieldContentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypeFieldContentRepository::class)
 */
class TypeFieldContent
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=AdditionalField::class, mappedBy="type")
     */
    private $additionalFields;

    public function __construct()
    {
        $this->additionalFields = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|AdditionalField[]
     */
    public function getAdditionalFields(): Collection
    {
        return $this->additionalFields;
    }

    public function addAdditionalField(AdditionalField $additionalField): self
    {
        if (!$this->additionalFields->contains($additionalField)) {
            $this->additionalFields[] = $additionalField;
            $additionalField->setType($this);
        }

        return $this;
    }

    public function removeAdditionalField(AdditionalField $additionalField): self
    {
        if ($this->additionalFields->removeElement($additionalField)) {
            // set the owning side to null (unless already changed)
            if ($additionalField->getType() === $this) {
                $additionalField->setType(null);
            }
        }

        return $this;
    }
}
