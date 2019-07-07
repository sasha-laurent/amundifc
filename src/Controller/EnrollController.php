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
use App\Service\GameManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Repository\GameRepository;


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
    public function indexAction(Request $request, GameRepository $gameRepo, GameManager $gameManager) 
    {
        $nextGame = $gameRepo->findNextGame();
        $player = new Player();
        $session = new Session();
        $form = $this->createForm(PlayerType::class, $player);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$nextGame instanceof Game){
                $session->getFlashBag()->add('error', 'Pas de prochain match défini.');
            }
            
            $player = $form->getData();
            $team = $gameManager->getTeamToFill($nextGame);
            $team->addPlayer($player);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($player);
            $entityManager->flush();
            
            $session->getFlashBag()->add('info', 'Inscription réussie ! Tu peux consulter les équipes.');

            return $this->redirectToRoute('teams');
        }
        
        return $this->render('enroll/index.html.twig', [
            'nextGame' => $nextGame,
            'form' => $form->createView()
        ]);
    }
}
