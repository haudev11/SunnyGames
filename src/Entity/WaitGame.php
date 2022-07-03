<?php

namespace App\Entity;

use App\Repository\WaitGameRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WaitGameRepository::class)
 */
class WaitGame
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="WaitGame", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $UserID;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $MinElo;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $MaxElo;

    /**
     * @ORM\Column(type="datetime")
     */
    private $WaitAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserID(): ?User
    {
        return $this->UserID;
    }

    public function setUserID(User $UserID): self
    {
        $this->UserID = $UserID;

        return $this;
    }

    public function getMinElo(): ?int
    {
        return $this->MinElo;
    }

    public function setMinElo(?int $MinElo): self
    {
        $this->MinElo = $MinElo;

        return $this;
    }

    public function getMaxElo(): ?int
    {
        return $this->MaxElo;
    }

    public function setMaxElo(?int $MaxElo): self
    {
        $this->MaxElo = $MaxElo;

        return $this;
    }

    public function getWaitAt(): ?\DateTimeInterface
    {
        return $this->WaitAt;
    }

    public function setWaitAt(\DateTimeInterface $WaitAt): self
    {
        $this->WaitAt = $WaitAt;

        return $this;
    }
}
