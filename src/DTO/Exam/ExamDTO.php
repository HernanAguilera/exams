<?php

namespace App\DTO\Exam;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ExamDTO
{
    /**
     * @Assert\NotNull
     */
    protected $name;

    /**
     * @Assert\NotNull
     */
    protected $company;

    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of company
     */ 
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set the value of company
     *
     * @return  self
     */ 
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }
}
