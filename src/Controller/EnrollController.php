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
use App\Form\PlayerType;
use App\Entity\Player;
use App\Entity\Game;
use App\Service\GameManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use App\FormHandler\FormHandler;


/**
 * Description of EnrollController
 *
 * @author sasha
 */
class EnrollController extends AbstractController
{
    /**
     * @Route("/", name="enroll")
     * 
     * @return type
     */
    public function indexAction(Request $request, GameManager $gameManager, FormHandler $formHandler) 
    {
        $nextGame = !empty($gameManager->findNextGames()) ? $gameManager->findNextGames()[0] : null;
        
        if ($nextGame instanceof Game && $nextGame->isFull()) {
            return $this->render('enroll/index.html.twig', [
                'nextGame' => $nextGame,
            ]);
        }
        
        $player = new Player();

        $newPlayerForm = $this->createForm(PlayerType::class, $player);
        $newPlayerForm->handleRequest($request);

        if ($newPlayerForm->isSubmitted() && $newPlayerForm->isValid()) {
            $formHandler->handleNewPlayerForm($newPlayerForm, new Session(), $nextGame);

            return $this->redirectToRoute('admin');
        }
        
        return $this->render('enroll/index.html.twig', [
            'nextGame' => $nextGame,
            'form' => $newPlayerForm->createView()
        ]);
    }
}
