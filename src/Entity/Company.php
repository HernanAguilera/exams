<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CompanyRepository::class)
 */
class Company
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
    private $commercial_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $legal_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tax_id;

    /**
     * @ORM\ManyToMany(targetEntity=Exam::class, mappedBy="companies")
     */
    private $exams;

    public function __construct()
    {
        $this->exams = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommercialName(): ?string
    {
        return $this->commercial_name;
    }

    public function setCommercialName(string $commercial_name): self
    {
        $this->commercial_name = $commercial_name;

        return $this;
    }

    public function getLegalName(): ?string
    {
        return $this->legal_name;
    }

    public function setLegalName(?string $legal_name): self
    {
        $this->legal_name = $legal_name;

        return $this;
    }

    public function getTaxId(): ?string
    {
        return $this->tax_id;
    }

    public function setTaxId(?string $tax_id): self
    {
        $this->tax_id = $tax_id;

        return $this;
    }

    /**
     * @return Collection|Exam[]
     */
    public function getExams(): Collection
    {
        return $this->exams;
    }

    public function addExam(Exam $exam): self
    {
        if (!$this->exams->contains($exam)) {
            $this->exams[] = $exam;
            $exam->addCompany($this);
        }

        return $this;
    }

    public function removeExam(Exam $exam): self
    {
        if ($this->exams->removeElement($exam)) {
            $exam->removeCompany($this);
        }

        return $this;
    }
}
