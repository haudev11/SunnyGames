<?php

namespace App\Controller;

use App\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/game")
 */

class GameController extends AbstractController
{
    /**
     * @Route("/{id}", name="app_view_game")
     */
    public function view($id, EntityManagerInterface $entityManager) : Response 
    {

        $game = $entityManager->getRepository(Game::class)->findOneBy(array('id' => $id)); 
        $gamePlay = $game->getGamePlay();
        return $this->render('game/view.html.twig', [
            'gamePlay' => $gamePlay,
            'currentGame'=> $game,
        ]);
    }
}
