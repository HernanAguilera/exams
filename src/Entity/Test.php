<?php

namespace App\Entity;

use App\Repository\TestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TestRepository::class)
 */
class Test
{
    public const RESERVED = 'Reserved';
    public const IN_PROGRESS = 'In progress';
    public const CANCELED = 'Canceled';
    public const FINISHIED = 'Finishied';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Exam::class, inversedBy="tests")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull
     */
    private $exam;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="tests")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $status;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Assert\Date
     */
    private $date;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $attended;

    /**
     * @ORM\OneToMany(targetEntity=Answer::class, mappedBy="test")
     */
    private $answers;

    public function __construct()
    {
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getAttended(): ?bool
    {
        return $this->attended;
    }

    public function setAttended(?bool $attended): self
    {
        $this->attended = $attended;

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
            $answer->setTest($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getTest() === $this) {
                $answer->setTest(null);
            }
        }

        return $this;
    }
}
