<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $Name;

    /**
     * @ORM\Column(type="integer")
     */
    private $Elo;

    /**
     * @ORM\Column(type="datetime")
     */
    private $Online;

    /**
     * @ORM\OneToMany(targetEntity=Game::class, mappedBy="UserOne")
     */
    private $GameUserOne;

    /**
     * @ORM\OneToMany(targetEntity=Game::class, mappedBy="UserTwo")
     */
    private $GameUserTwo;

    /**
     * @ORM\OneToOne(targetEntity=CurrentGame::class, mappedBy="UserOne")
     */
    private $CurrentGameOne;

    /**
     * @ORM\OneToOne(targetEntity=CurrentGame::class, mappedBy="UserTwo", cascade={"persist", "remove"})
     */
    private $CurrentGameTwo;

    /**
     * @ORM\OneToOne(targetEntity=WaitGame::class, mappedBy="UserID", cascade={"persist", "remove"})
     */
    private $WaitGame;

    /**
     * @ORM\OneToOne(targetEntity=InviteGame::class, mappedBy="FromID", cascade={"persist", "remove"})
     */
    private $inviteGame;

    /**
     * @ORM\OneToMany(targetEntity=InviteGame::class, mappedBy="ToID")
     */
    private $InvitedGame;

    public function __construct()
    {
        $this->GameUserOne = new ArrayCollection();
        $this->GameUserTwo = new ArrayCollection();
        $this->InvitedGame = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getElo(): ?int
    {
        return $this->Elo;
    }

    public function setElo(int $Elo): self
    {
        $this->Elo = $Elo;

        return $this;
    }

    public function isOnline(): bool
    {
        $now = new \DateTime();
        $timeInBetween = $now->getTimestamp() - $this->Online->getTimestamp();
        return $timeInBetween <= 60;
    }

    public function setOnline(\DateTimeInterface $Online): self
    {
        $this->Online = $Online;

        return $this;
    }

    /**
     * @return Collection<int, Game>
     */
    public function getGameUserOne(): Collection
    {
        return $this->GameUserOne;
    }

    public function addGameUserOne(Game $gameUserOne): self
    {
        if (!$this->GameUserOne->contains($gameUserOne)) {
            $this->GameUserOne[] = $gameUserOne;
            $gameUserOne->setUserOne($this);
        }

        return $this;
    }

    public function removeGameUserOne(Game $gameUserOne): self
    {
        if ($this->GameUserOne->removeElement($gameUserOne)) {
            // set the owning side to null (unless already changed)
            if ($gameUserOne->getUserOne() === $this) {
                $gameUserOne->setUserOne(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Game>
     */
    public function getGameUserTwo(): Collection
    {
        return $this->GameUserTwo;
    }

    public function addGameUserTwo(Game $gameUserTwo): self
    {
        if (!$this->GameUserTwo->contains($gameUserTwo)) {
            $this->GameUserTwo[] = $gameUserTwo;
            $gameUserTwo->setUserTwo($this);
        }

        return $this;
    }

    public function removeGameUserTwo(Game $gameUserTwo): self
    {
        if ($this->GameUserTwo->removeElement($gameUserTwo)) {
            // set the owning side to null (unless already changed)
            if ($gameUserTwo->getUserTwo() === $this) {
                $gameUserTwo->setUserTwo(null);
            }
        }

        return $this;
    }

    public function getCurrentGameOne(): ?CurrentGame
    {
        return $this->CurrentGameOne;
    }

    public function setCurrentGameOne(CurrentGame $CurrentGameOne): self
    {
        // set the owning side of the relation if necessary
        if ($CurrentGameOne->getUserOne() !== $this) {
            $CurrentGameOne->setUserOne($this);
        }

        $this->CurrentGameOne = $CurrentGameOne;

        return $this;
    }

    public function getCurrentGameTwo(): ?CurrentGame
    {
        return $this->CurrentGameTwo;
    }

    public function setCurrentGameTwo(CurrentGame $CurrentGameTwo): self
    {
        // set the owning side of the relation if necessary
        if ($CurrentGameTwo->getUserTwo() !== $this) {
            $CurrentGameTwo->setUserTwo($this);
        }

        $this->CurrentGameTwo = $CurrentGameTwo;

        return $this;
    }
    public function getCurrentGame(): ?CurrentGame
    {
        if ($this->getCurrentGameOne() === null){
            return $this->getCurrentGameOne();
        }
        return $this->getCurrentGameTwo();
    }
    public function getWaitGame(): ?WaitGame
    {
        return $this->WaitGame;
    }

    public function setWaitGame(WaitGame $WaitGame): self
    {
        // set the owning side of the relation if necessary
        if ($WaitGame->getUserID() !== $this) {
            $WaitGame->setUserID($this);
        }

        $this->WaitGame = $WaitGame;

        return $this;
    }

    public function getInviteGame(): ?InviteGame
    {
        return $this->inviteGame;
    }

    public function setInviteGame(InviteGame $inviteGame): self
    {
        // set the owning side of the relation if necessary
        if ($inviteGame->getFromID() !== $this) {
            $inviteGame->setFromID($this);
        }

        $this->inviteGame = $inviteGame;

        return $this;
    }

    /**
     * @return Collection<int, InviteGame>
     */
    public function getInvitedGame(): Collection
    {
        return $this->InvitedGame;
    }

    public function addInvitedGame(InviteGame $invitedGame): self
    {
        if (!$this->InvitedGame->contains($invitedGame)) {
            $this->InvitedGame[] = $invitedGame;
            $invitedGame->setToID($this);
        }

        return $this;
    }

    public function removeInvitedGame(InviteGame $invitedGame): self
    {
        if ($this->InvitedGame->removeElement($invitedGame)) {
            // set the owning side to null (unless already changed)
            if ($invitedGame->getToID() === $this) {
                $invitedGame->setToID(null);
            }
        }

        return $this;
    }
}
