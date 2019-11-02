<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TeamRepository")
 */
class Team
{
    const MAX_PLAYERS = 9;
    
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Game", inversedBy="teams")
     */
    private $relatedGame;

    /**
     * @var ArrayCollection
     * 
     * @ORM\ManyToMany(targetEntity="App\Entity\Player", inversedBy="teams")
     */
    private $players;

    public function __construct()
    {
        $this->players = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRelatedGame(): ?Game
    {
        return $this->relatedGame;
    }

    public function setRelatedGame(?Game $relatedGame): self
    {
        $this->relatedGame = $relatedGame;

        return $this;
    }

    /**
     * @return Collection|Player[]
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Player $player): self
    {
        if (!$this->players->contains($player)) {
            $this->players[] = $player;
        }

        return $this;
    }

    public function removePlayer(Player $player): self
    {
        if ($this->players->contains($player)) {
            $this->players->removeElement($player);
        }

        return $this;
    }
    
    public function isFull($max = self::MAX_PLAYERS): bool
    {
        return $this->players->count() >= $max;
    }
}
