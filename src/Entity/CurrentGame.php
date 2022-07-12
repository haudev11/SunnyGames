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

    /**
     * @ORM\Column(type="datetime")
     */
    private $LastMoveTime;
    
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
    public function ValidateMove($move):bool
    {       
        for ($i = 0; $i < strlen($this->GamePlay); $i += 4){
            if ($move[0] == $this->GamePlay[$i] && $move[1] == $this->GamePlay[$i+1] &&
                $move[2] == $this->GamePlay[$i+2] &&$move[3] == $this->GamePlay[$i+3]){
                    return false;
                }
        }
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
    public function getUserMove() :User {

        if (strlen($this->GamePlay) / 4 % 2 == 0) {
            return $this->UserOne;
        }
        return $this->UserTwo;
    }
    public function getLastMoveTime(): ?\DateTimeInterface
    {
        return $this->LastMoveTime;
    }
    public function setLastMoveTime(\DateTimeInterface $LastMoveTime): self
    {
        $this->LastMoveTime = $LastMoveTime;
        return $this;
    }
    public function updateTimeMove(\DateTimeInterface $now): void
    {
        if ($this->getUserMove() === $this->UserOne ){
            $this->TimeUserOne -=  ($now->getTimestamp() - $this->LastMoveTime->getTimestamp());
        }
        $this->TimeUserTwo -=  ($now->getTimestamp() - $this->LastMoveTime->getTimestamp());
        return;
    } 
    public function checkWinGame():int
    {
        $a = array(
                    [], [], [], [], [], [], [], [], [], [],
                    [], [], [], [], [], [], [], [], [], [],
                    [], [], [], [], [], [], [], [], [], [],
                );
        for ($i = 0; $i < 25; $i++){
            for ($j = 0; $j < 25; $j++){
                $a[$i][$j] = 0;
            }
        }
        $k = 1;
        for ($i = 0; $i < strlen($this->GamePlay); $i += 4){
            $row = (int)($this->GamePlay[$i] . $this->GamePlay[$i+1]);
            $column = (int)($this->GamePlay[$i+2] . $this->GamePlay[$i+3]);
            if ($k == 1){
                $a[$row][$column] = 1;
            } else {
                $a[$row][$column] = 2;
            }
            $k *= -1;
        }
    
        for ($i = 0; $i < 22; $i++){
            for ($j = 0; $j < 22; $j++){

                // check row line
                if ($a[$i][$j+0] == 1 && $a[$i][$j+1] == 1 && 
                    $a[$i][$j+2] == 1 && $a[$i][$j+3] == 1 && $a[$i][$j+4] == 1){
                        return 1;
                    }
                if ($a[$i][$j+0] == 2 && $a[$i][$j+1] == 2 && 
                    $a[$i][$j+2] == 2 && $a[$i][$j+3] == 2 && $a[$i][$j+4] == 2){
                        return 2;
                    }
                // check column line
                if ($a[$i+0][$j] == 1 && $a[$i+1][$j] == 1 && 
                    $a[$i+2][$$j] == 1 && $a[$i+3][$j] == 1 && $a[$i+4][$j] == 1){
                        return 1;
                    }
                if ($a[$i+0][$j] == 2 && $a[$i+1][$j] == 2 && 
                    $a[$i+2][$j] == 2 && $a[$i+3][$j] == 2 && $a[$i+4][$j] == 2){
                        return 2;
                    }
                // check d$iagonal l$ine
                if ($a[$i+0][$j+0] == 1 && $a[$i+1][$j+1] == 1 && 
                    $a[$i+2][$j+2] == 1 && $a[$i+3][$j+3] == 1 && $a[$i+4][$j+4] == 1){
                    return 1;
                }
                if ($a[$i+0][$j+0] == 2 && $a[$i+1][$j+1] == 2 && 
                    $a[$i+2][$j+2] == 2 && $a[$i+3][$j+3] == 2 && $a[$i+4][$j+4] == 2){
                    return 2;
                }
            }
        }
        return 0;
    }
}
