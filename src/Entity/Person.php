<?php

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PersonRepository::class)
 */
class Person
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="people")
     */
    private $users;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $first_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $last_name;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $birthdate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $dni;

    /**
     * @ORM\Column(type="boolean", options={"default": 0})
     */
    private $active;

    /**
     * @ORM\OneToOne(targetEntity=Profile::class, mappedBy="person", cascade={"persist", "remove"})
     */
    private $profile;

    /**
     * @ORM\OneToMany(targetEntity=CommunicationChannel::class, mappedBy="person")
     */
    private $communicationChannels;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->communicationChannels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(?\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getDni(): ?string
    {
        return $this->dni;
    }

    public function setDni(?string $dni): self
    {
        $this->dni = $dni;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(Profile $profile): self
    {
        // set the owning side of the relation if necessary
        if ($profile->getPerson() !== $this) {
            $profile->setPerson($this);
        }

        $this->profile = $profile;

        return $this;
    }

    /**
     * @return Collection|CommunicationChannel[]
     */
    public function getCommunicationChannels(): Collection
    {
        return $this->communicationChannels;
    }

    public function addCommunicationChannel(CommunicationChannel $communicationChannel): self
    {
        if (!$this->communicationChannels->contains($communicationChannel)) {
            $this->communicationChannels[] = $communicationChannel;
            $communicationChannel->setPerson($this);
        }

        return $this;
    }

    public function removeCommunicationChannel(CommunicationChannel $communicationChannel): self
    {
        if ($this->communicationChannels->removeElement($communicationChannel)) {
            // set the owning side to null (unless already changed)
            if ($communicationChannel->getPerson() === $this) {
                $communicationChannel->setPerson(null);
            }
        }

        return $this;
    }

}
