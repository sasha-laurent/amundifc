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
     * @return type
     */
    public function indexAction(\App\Repository\GameRepository $gameRepo) 
    {
        $nextGame = $gameRepo->findNextGame();
        
        return $this->render('admin/index.html.twig', ['nextGame' => $nextGame]);
    }
}
