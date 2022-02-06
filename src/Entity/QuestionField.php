<?php

namespace App\Entity;

use App\Repository\QuestionFieldRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=QuestionFieldRepository::class)
 */
class QuestionField
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=QuestionQuestionField::class, mappedBy="question_field")
     */
    private $questionQuestionFields;

    public function __construct()
    {
        $this->questionQuestionFields = new ArrayCollection();
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
     * @return Collection|QuestionQuestionField[]
     */
    public function getQuestionQuestionFields(): Collection
    {
        return $this->questionQuestionFields;
    }

    public function addQuestionQuestionField(QuestionQuestionField $questionQuestionField): self
    {
        if (!$this->questionQuestionFields->contains($questionQuestionField)) {
            $this->questionQuestionFields[] = $questionQuestionField;
            $questionQuestionField->setQuestionField($this);
        }

        return $this;
    }

    public function removeQuestionQuestionField(QuestionQuestionField $questionQuestionField): self
    {
        if ($this->questionQuestionFields->removeElement($questionQuestionField)) {
            // set the owning side to null (unless already changed)
            if ($questionQuestionField->getQuestionField() === $this) {
                $questionQuestionField->setQuestionField(null);
            }
        }

        return $this;
    }
}
