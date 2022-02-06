<?php

namespace App\Entity;

use App\Repository\QuestionQuestionFieldRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=QuestionQuestionFieldRepository::class)
 */
class QuestionQuestionField
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotNull
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity=Question::class, inversedBy="questionQuestionFields")
     * @ORM\JoinColumn(nullable=false)
     */
    private $question;

    /**
     * @ORM\ManyToOne(targetEntity=QuestionField::class, inversedBy="questionQuestionFields")
     * @ORM\JoinColumn(nullable=false)
     */
    private $question_field;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getQuestionField(): ?QuestionField
    {
        return $this->question_field;
    }

    public function setQuestionField(?QuestionField $question_field): self
    {
        $this->question_field = $question_field;

        return $this;
    }
}
