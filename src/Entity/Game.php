<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameRepository::class)
 */
class Game
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", length=1700)
     */
    private $GamePlay;

    /**
     * @ORM\Column(type="integer")
     */
    private $Result;

    /**
     * @ORM\Column(type="datetime")
     */
    private $GameAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="GameUserOne")
     * @ORM\JoinColumn(nullable=false)
     */
    private $UserOne;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="GameUserTwo")
     * @ORM\JoinColumn(nullable=false)
     */
    private $UserTwo;


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

    public function getResult(): ?int
    {
        return $this->Result;
    }

    public function setResult(int $Result): self
    {
        $this->Result = $Result;

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

    public function getUserOne(): ?User
    {
        return $this->UserOne;
    }

    public function setUserOne(?User $UserOne): self
    {
        $this->UserOne = $UserOne;

        return $this;
    }

    public function getUserTwo(): ?User
    {
        return $this->UserTwo;
    }

    public function setUserTwo(?User $UserTwo): self
    {
        $this->UserTwo = $UserTwo;

        return $this;
    }
}
