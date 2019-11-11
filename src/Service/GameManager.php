<?php

/*
 * Copyright (C) 2019 sasha
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace App\Service;

use App\Entity\Game;
use App\Entity\Team;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use App\Exception\TooManyTeamsException;

/**
 * Description of GameManager
 *
 * @author sasha
 */
class GameManager {
    
    /** @var EntityManagerInterface $em*/
    private $em;
    
    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em) 
    {
        $this->em = $em;
    }
    
    /**
     * @param Game $game
     * 
     * @return Team
     * 
     * @throws TooManyTeamsException
     */
    public function getTeamToFill(Game $game) : Team 
    {
        if ($game->isFull()) {
            throw new TooManyPlayersException('Both teams are full !');
        }

        $teamCount = $game->getTeams()->count();
        
        if (0 === $teamCount) {
            $newTeam = $game->addTeam(new Team())->getTeams()[0];
            $this->em->persist($newTeam);
            $this->em->flush();
            
            return $newTeam;
        }

        $firstTeam = $game->getTeams()->first();

        if (1 === $teamCount) {
            if (0 === $firstTeam->getPlayers()->count()) {
                return $firstTeam;
            }
            
            $game->addTeam($newSecondTeam = new Team());
            $this->em->persist($newSecondTeam);
            $this->em->flush();
            
            return $newSecondTeam;
        }
        
        if (2 === $teamCount) {
            $secondTeam = $game->getTeams()->next();
            
            return $firstTeam->getPlayers()->count() > $secondTeam->getPlayers()->count() ? $secondTeam : $firstTeam;
        }
        
        throw new TooManyTeamsException('You can\'t have more than two teams !');
    }
    
    /**
     * @return array
     */
    public function findNextGames() : ArrayCollection
    {
        return new ArrayCollection($this->em->getRepository(Game::class)->findNextGames());
    }
    
    /**
     * @return Game
     */
    public function find(int $id) : Game
    {
        return $this->em->getRepository(Game::class)->find($id);
    }

        /**
     * @param Game $game
     * 
     * @return bool
     */
    public function validateNewGame(Game $game) : bool
    {
        $gameRepo = $this->em->getRepository(Game::class);
        
        return !$gameRepo->findOneBy(['date' => $game->getDate()]) instanceof Game;
    }
    
    public function saveGame(Game $game) : bool
    {
        $this->em->persist($game);
        $this->em->flush();
        
        return true;
    }
}
