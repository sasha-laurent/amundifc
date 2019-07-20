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
use App\Entity\Player;
use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Description of PlayerManager
 *
 * @author sasha
 */
class PlayerManager {
    
    /** @var EntityManagerInterface */
    private $entityManager;
    
    /**
     * @param \App\Service\EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }
    
    /**
     * @param Game $game
     * @param Player $player
     * 
     * @return int|boolean
     */
    public function switchTeam(Game $game, Player $player) {
        
        $oldTeam = $this->findPlayerTeamGame($game, $player);
        
        if (!$oldTeam instanceof Team) {
            return false;
        }

        $oldTeam->removePlayer($player);
        
        foreach ($game->getTeams() as $team) {
            if ($team->getId() !== $oldTeam->getId()) {
                $player->addTeam($team);
                $this->entityManager->flush();
                
                return $team->getId();
            }
        }
        
        return false;
    }
    
    /**
     * @param Game $game
     * @param Player $player
     * 
     * @return int|boolean
     */
    public function removeFromGame(Game $game, Player $player) {
        
        $oldTeam = $this->findPlayerTeamGame($game, $player);
        
        if (!$oldTeam instanceof Team) {
            return false;
        }

        $oldTeam->removePlayer($player);
        $this->entityManager->flush();
        
        return true;
    }
    
    /**
     * @param Game $game
     * @param Player $player
     * 
     * @return Team|boolean
     */
    private function findPlayerTeamGame(Game $game, Player $player) {
        foreach ($game->getTeams() as $gameTeam) {
            foreach ($player->getTeams() as $playerTeam) {
                if ($gameTeam->getId() === $playerTeam->getId()) {
                    return $playerTeam;
                }
            }
        }
        
        return false;
    }
}
