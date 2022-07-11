<?php

namespace App\Entity;

use App\Repository\InviteGameRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InviteGameRepository::class)
 */
class InviteGame
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="inviteGame")
     * @ORM\JoinColumn(nullable=false)
     */
    private $FromID;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="InvitedGame")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ToID;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFromID(): ?User
    {
        return $this->FromID;
    }

    public function setFromID(User $FromID): self
    {
        $this->FromID = $FromID;

        return $this;
    }

    public function getToID(): ?User
    {
        return $this->ToID;
    }

    public function setToID(?User $ToID): self
    {
        $this->ToID = $ToID;

        return $this;
    }
}
