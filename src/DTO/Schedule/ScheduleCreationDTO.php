<?php

namespace App\DTO\Schedule;

use Symfony\Component\Validator\Constraints as Assert;

class ScheduleCreationDTO
{
    /**
     * @Assert\NotNull
     * @Assert\Positive
     */
    protected $exam;

    /**
     * @Assert\NotNull
     * @Assert\Date
     * @var string A "Y-m-d" formatted value
     */
    protected $date;


    /**
     * Get the value of exam
     */ 
    public function getExam()
    {
        return $this->exam;
    }

    /**
     * Set the value of exam
     *
     * @return  self
     */ 
    public function setExam($exam)
    {
        $this->exam = $exam;

        return $this;
    }

    /**
     * Get the value of date
     */ 
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set the value of date
     *
     * @return  self
     */ 
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }
}
