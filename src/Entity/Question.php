<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=QuestionRepository::class)
 */
class Question
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Exam::class, inversedBy="questions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $exam;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull
     */
    private $question;

    /**
     * @ORM\OneToMany(targetEntity=QuestionOption::class, mappedBy="question")
     */
    private $questionOptions;

    /**
     * @ORM\OneToMany(targetEntity=QuestionQuestionField::class, mappedBy="question")
     */
    private $questionQuestionFields;

    /**
     * @ORM\OneToMany(targetEntity=Answer::class, mappedBy="question")
     */
    private $answers;

    public function __construct()
    {
        $this->questionOptions = new ArrayCollection();
        $this->questionQuestionFields = new ArrayCollection();
        $this->answers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExam(): ?Exam
    {
        return $this->exam;
    }

    public function setExam(?Exam $exam): self
    {
        $this->exam = $exam;

        return $this;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    /**
     * @return Collection|QuestionOption[]
     */
    public function getQuestionOptions(): Collection
    {
        return $this->questionOptions;
    }

    public function addQuestionOption(QuestionOption $questionOption): self
    {
        if (!$this->questionOptions->contains($questionOption)) {
            $this->questionOptions[] = $questionOption;
            $questionOption->setQuestion($this);
        }

        return $this;
    }

    public function removeQuestionOption(QuestionOption $questionOption): self
    {
        if ($this->questionOptions->removeElement($questionOption)) {
            // set the owning side to null (unless already changed)
            if ($questionOption->getQuestion() === $this) {
                $questionOption->setQuestion(null);
            }
        }

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
            $questionQuestionField->setQuestion($this);
        }

        return $this;
    }

    public function removeQuestionQuestionField(QuestionQuestionField $questionQuestionField): self
    {
        if ($this->questionQuestionFields->removeElement($questionQuestionField)) {
            // set the owning side to null (unless already changed)
            if ($questionQuestionField->getQuestion() === $this) {
                $questionQuestionField->setQuestion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Answer[]
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers[] = $answer;
            $answer->setQuestion($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getQuestion() === $this) {
                $answer->setQuestion(null);
            }
        }

        return $this;
    }
}
