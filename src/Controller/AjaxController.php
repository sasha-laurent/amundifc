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

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Game;
use App\Entity\Player;
use App\Entity\Team;
use App\Service\PlayerManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Description of AjaxController
 *
 * @author sasha
 */
class AjaxController extends AbstractController {

    /**
     * @Route("/switch/{game}/{player}", name="switch_team")
     * 
     * @ParamConverter("game", class="App:Game")
     * @ParamConverter("player", class="App:Player")
     * 
     * @return JsonResponse
     */
    public function switchTeamAction(Game $game, Player $player, PlayerManager $playerManager) 
    {        
        $newTeam = $playerManager->switchTeam($game, $player);
        $alerts = [];
        
        if ($newTeam->isFull(Team::MAX_PLAYERS + 1)) {
            $alerts []= [
                'type' => 'warning', 
                'message' => 'One team has more than the maximum amount of players !'
            ];
        }
        
        return new JsonResponse([
            'game' => $game->getId(),
            'player' => $player->getId(),
            'newTeamId' => $newTeam->getId(),
            'alerts' => $alerts
        ]);
    }

    /**
     * @Route("/remove/{game}/{player}", name="remove_player")
     * 
     * @ParamConverter("game", class="App:Game")
     * @ParamConverter("player", class="App:Player")
     * 
     * @return JsonResponse
     */
    public function removePlayerAction(Game $game, Player $player, PlayerManager $playerManager) 
    {        
        return new JsonResponse([
            'game' => $game->getId(),
            'player' => $player->getId(),
            'removed' => $playerManager->removeFromGame($game, $player)
        ]);
    }
}
