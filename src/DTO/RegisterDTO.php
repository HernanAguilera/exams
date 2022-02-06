<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class RegisterDTO
{
    /**
     * @Assert\NotNull
     * @Assert\Email
     */
    protected $email;

    /**
     * @Assert\NotNull
     */
    protected $password;

    /**
     * @Assert\NotNull
     */
    protected $password_confirm;

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        $obj = $context->getValue();
        if ($obj->getPassword() !== $obj->getPasswordConfirm()) {
            $context->buildViolation('La confirmación del password no coincide')
                ->atPath('password_confirm')
                ->addViolation();
        }
    }

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of password
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of password_confirm
     */ 
    public function getPasswordConfirm()
    {
        return $this->password_confirm;
    }

    /**
     * Set the value of password_confirm
     *
     * @return  self
     */ 
    public function setPasswordConfirm($password_confirm)
    {
        $this->password_confirm = $password_confirm;

        return $this;
    }
}
