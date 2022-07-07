<?php

namespace App\Controller;

use App\Entity\CurrentGame;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
/**
 * @Route("/live", name="app_current_game")
 */
class CurrentGameController extends AbstractController
{
    /**
     * @Route("/view/{id}", name="app_current_game_view")
     */
    public function view($id, EntityManagerInterface $entityManager): void
    {
        $currentGames = $entityManager->getRepository(CurrentGame::class);
        $currentGame = $currentGames->findOneBy(['id' => $id]);
        dd($currentGame);

    }

    /**
     * @Route("/move/{id}", name="app_current_game_move")
     */
    public function move($id, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $move = $request->query->get('move');
        $currentGames = $entityManager->getRepository(CurrentGame::class);
        $currentGame = $currentGames->findOneBy(['id' => $id]);
        $gamePlay = $currentGame->getGamePlay();
        $gamePlay = $gamePlay . $move;
        $currentGame->setGamePlay($gamePlay);
        $currentGames->add($currentGame, true);
        dd($currentGame);
        return $this->json(['property'=>'value'],200);
    }
}
