<?php

namespace App\Entity;

use App\Repository\CommunicationChannelTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommunicationChannelTypeRepository::class)
 */
class CommunicationChannelType
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
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=CommunicationChannel::class, mappedBy="type")
     */
    private $communicationChannels;

    public function __construct()
    {
        $this->communicationChannels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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
            $communicationChannel->setType($this);
        }

        return $this;
    }

    public function removeCommunicationChannel(CommunicationChannel $communicationChannel): self
    {
        if ($this->communicationChannels->removeElement($communicationChannel)) {
            // set the owning side to null (unless already changed)
            if ($communicationChannel->getType() === $this) {
                $communicationChannel->setType(null);
            }
        }

        return $this;
    }
}
