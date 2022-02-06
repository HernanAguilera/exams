<?php

namespace App\Entity;

use App\Repository\QuestionOptionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=QuestionOptionRepository::class)
 */
class QuestionOption
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Question::class, inversedBy="questionOptions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $question;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull
     */
    private $question_option;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_correct;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getQuestionOption(): ?string
    {
        return $this->question_option;
    }

    public function setQuestionOption(string $question_option): self
    {
        $this->question_option = $question_option;

        return $this;
    }

    public function getIsCorrect(): ?bool
    {
        return $this->is_correct;
    }

    public function setIsCorrect(bool $is_correct): self
    {
        $this->is_correct = $is_correct;

        return $this;
    }
}
