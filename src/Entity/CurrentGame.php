<?php

namespace App\Entity;

use App\Repository\CurrentGameRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CurrentGameRepository::class)
 */
class CurrentGame
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="blob")
     */
    private $GamePlay;

    /**
     * @ORM\Column(type="datetime")
     */
    private $TimeUserOne;

    /**
     * @ORM\Column(type="datetime")
     */
    private $TimeUserTwo;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="CurrentGameOne", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $UserOne;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="CurrentGameTwo", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $UserTwo;

    /**
     * @ORM\Column(type="datetime")
     */
    private $GameAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGamePlay()
    {
        return $this->GamePlay;
    }

    public function setGamePlay($GamePlay): self
    {
        $this->GamePlay = $GamePlay;

        return $this;
    }

    public function getTimeUserOne(): ?\DateTimeInterface
    {
        return $this->TimeUserOne;
    }

    public function setTimeUserOne(\DateTimeInterface $TimeUserOne): self
    {
        $this->TimeUserOne = $TimeUserOne;

        return $this;
    }

    public function getTimeUserTwo(): ?\DateTimeInterface
    {
        return $this->TimeUserTwo;
    }

    public function setTimeUserTwo(\DateTimeInterface $TimeUserTwo): self
    {
        $this->TimeUserTwo = $TimeUserTwo;

        return $this;
    }

    public function getUserOne(): ?User
    {
        return $this->UserOne;
    }

    public function setUserOne(User $UserOne): self
    {
        $this->UserOne = $UserOne;

        return $this;
    }

    public function getUserTwo(): ?User
    {
        return $this->UserTwo;
    }

    public function setUserTwo(User $UserTwo): self
    {
        $this->UserTwo = $UserTwo;

        return $this;
    }

    public function getGameAt(): ?\DateTimeInterface
    {
        return $this->GameAt;
    }

    public function setGameAt(\DateTimeInterface $GameAt): self
    {
        $this->GameAt = $GameAt;

        return $this;
    }
}
