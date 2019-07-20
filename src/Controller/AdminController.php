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
use App\Form\GameType;
use App\Repository\GameRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of AdminController
 *
 * @author sasha
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     * 
     * @param Request        $request
     * @param GameRepository $gameRepo
     * 
     * @return Response
     */
    public function indexAction(Request $request, GameRepository $gameRepo) 
    {
        $nextGames = $gameRepo->findNextGames();
        $nextGame = array_shift($nextGames);
        
        $newGame = new Game();
        $newGameForm = $this->createForm(GameType::class, $newGame);
        
        $newGameForm->handleRequest($request);

        if ($newGameForm->isSubmitted() && $newGameForm->isValid()) {
            $newGame = $newGameForm->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($newGame);
            $entityManager->flush();

            return $this->redirectToRoute('admin');
        }
        
        
        return $this->render('admin/index.html.twig', [
            'nextGame' => $nextGame,
            'otherGames' => $nextGames,
            'newGameForm' => $newGameForm->createView()
        ]);
    }
}
