<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CurrentGameRepository;

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
     * @ORM\Column(type="text", length=1700)
     */
    private $GamePlay;

    
    /**
     * @ORM\Column(type="integer")
     */
    private $TimeUserOne;

    /**
     * @ORM\Column(type="integer")
     */
    private $TimeUserTwo;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="CurrentGameOne")
     * @ORM\JoinColumn(nullable=false)
     */
    private $UserOne;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="CurrentGameTwo")
     * @ORM\JoinColumn(nullable=false)
     */
    private $UserTwo;

    /**
     * @ORM\Column(type="datetime")
     */
    private $GameAt;
    
    public function __construct()
    {
        $this->NextMove = $this->UserOne;
    }
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

    public function getTimeUserOne(): int
    {
        return $this->TimeUserOne;
    }

    public function setTimeUserOne(int $TimeUserOne): self
    {
        $this->TimeUserOne = $TimeUserOne;

        return $this;
    }

    public function getTimeUserTwo(): int
    {
        return $this->TimeUserTwo;
    }

    public function setTimeUserTwo(int $TimeUserTwo): self
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

    // check validate move
    private function ValidateMove($move):bool
    {       
        return true;
    }
    public function Move(User $User, $move):void
    {
        $this->GamePlay =  $this->GamePlay . $move;

        if ($User != $this->NextMove){
            return;
        }
        if (!ValidateMove($move)){
            return;
        }
        $this->GamePlay =  $this->GamePlay . $move;
        if ($this->NextMove === $this->UserOne){
            $this->NextMove = $this->UserTwo;
        } else {
            $this->NextMove = $this->UserOne;
        }
    }
}
