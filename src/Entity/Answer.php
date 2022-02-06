<?php

namespace App\Entity;

use App\Repository\AnswerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnswerRepository::class)
 */
class Answer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Test::class, mappedBy="answer")
     */
    private $test;

    /**
     * @ORM\ManyToOne(targetEntity=Question::class, inversedBy="answers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $question;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $answer;

    public function __construct()
    {
        $this->test = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Test[]
     */
    public function getTest(): Collection
    {
        return $this->test;
    }

    public function addTest(Test $test): self
    {
        if (!$this->test->contains($test)) {
            $this->test[] = $test;
            $test->setAnswer($this);
        }

        return $this;
    }

    public function removeTest(Test $test): self
    {
        if ($this->test->removeElement($test)) {
            // set the owning side to null (unless already changed)
            if ($test->getAnswer() === $this) {
                $test->setAnswer(null);
            }
        }

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

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(?string $answer): self
    {
        $this->answer = $answer;

        return $this;
    }
}
