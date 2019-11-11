<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Exception\TooManyTeamsException;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GameRepository")
 */
class Game
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Team", mappedBy="relatedGame")
     */
    private $teams;

    public function __construct()
    {
        $this->teams = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    /**
     * @param \DateTimeInterface $date
     * 
     * @return \self
     */
    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection|Team[]
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    /**
     * @param \App\Entity\Team $team
     * 
     * @return \self
     * 
     * @throws TooManyTeamsException
     */
    public function addTeam(Team $team): self
    {
        if (count($this->teams) >= 2) {
            throw new TooManyTeamsException('You can\'t have more than two teams !');
        }
        
        if (!$this->teams->contains($team)) {
            $this->teams[] = $team;
            $team->setRelatedGame($this);
        }

        return $this;
    }

    /**
     * @param \App\Entity\Team $team
     * 
     * @return \self
     */
    public function removeTeam(Team $team): self
    {
        if ($this->teams->contains($team)) {
            $this->teams->removeElement($team);
            // set the owning side to null (unless already changed)
            if ($team->getRelatedGame() === $this) {
                $team->setRelatedGame(null);
            }
        }

        return $this;
    }
    
    /**
     * @param int $max
     *
     * @return bool
     */
    public function isFull($max = Team::MAX_PLAYERS*2): bool 
    {
        $countPlayers = 0;

        /** @var Team $team */
        foreach ($this->teams as $team) {
            $countPlayers += $team->getPlayers()->count();
        }
        
        return $countPlayers >= $max;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDateString(): ?string
    {
        return date_format($this->date, 'd/m/Y (l)');
    }
}
