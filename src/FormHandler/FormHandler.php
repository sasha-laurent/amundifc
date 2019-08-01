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

namespace App\FormHandler;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Form;
use App\Service\GameManager;
use App\Service\PlayerManager;
use App\Entity\Game;

/**
 * Description of GameFormHandler
 *
 * @author sasha
 */
class FormHandler {
    /** @var GameManager */
    private $gameManager;
    
    /** @var PlayerManager */
    private $playerManager;

    /**
     * @param GameManager $gameManager
     * @param PlayerManager $playerManager
     */
    public function __construct(GameManager $gameManager, PlayerManager $playerManager)
    {
        $this->gameManager = $gameManager;
        $this->playerManager = $playerManager;
    }

    /**
     * @param Form $form
     * @param Session $session
     * 
     * @return bool
     */
    public function handleNewGameForm(Form $form, Session $session) : bool
    {
        $newGame = $form->getData();

        if (!$this->gameManager->validateNewGame($newGame, $session)) {
            $session->getFlashBag()->add('danger', 'Il existe déjà un match à cette date... Voyons !!');

            return false;
        }

        $session->getFlashBag()->add('success', 'Match créé ! Il est temps de recruter !');

        return $this->gameManager->saveGame($newGame);
    }
    
    /**
     * @param Form $form
     * @param Session $session
     * @param Game $nextGame
     * 
     * @return bool
     */
    public function handleNewPlayerForm(Form $form, Session $session, Game $nextGame) : bool
    {
        if (!$nextGame instanceof Game){
            $session->getFlashBag()->add('danger', 'Pas de prochain match défini... Mais comment as-tu réussi cette prouesse ?!');

            return false;
        }

        $player = $form->getData();
        $team = $this->gameManager->getTeamToFill($nextGame);
        $team->addPlayer($player);

        $session->getFlashBag()->add('success', 'Inscription réussie ! Tu peux commencer à rentrer dans ton match ');
        
        return $this->playerManager->savePlayer($player);
    }
}
