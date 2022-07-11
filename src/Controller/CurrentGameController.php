<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\User;
use App\Entity\CurrentGame;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
/**
 * @Route("/live")
 */
class CurrentGameController extends AbstractController
{
    /**
     * @Route("/view/{id}", name="app_current_game_view")
     */
    public function view($id, EntityManagerInterface $entityManager): void
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        dd($user->getCurrentGame());
        $currentGames = $entityManager->getRepository(CurrentGame::class);
        $currentGame = $currentGames->findOneBy(['id' => $id]);
        $time = new \DateTime;
        dd( $time->getTimestamp() - $currentGame->getLastMoveTime()->getTimestamp());
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
        $users = $entityManager->getRepository(User::class);
        $userMove = $users->findOneBy(['id' => $currentGame->getUserMove()]);
        $gamePlay = $currentGame->getGamePlay();
        // check user 
        if ($user != $userMove) {
            return $this->json(['mes'=>'Invalid user'],200);
        }
        // check move 
        if (!$currentGame->ValidateMove($move)){
            return $this->json(['mes'=>'Invalid move'],200);
        }
        $gamePlay = $gamePlay . $move;
        $currentGame->setGamePlay($gamePlay);
        $currentGame->updateTimeMove(new \DateTime());
        $currentGames->add($currentGame, true);
        $result = $currentGame->checkWinGame();
        if ($result!=0){
            $doneGame = new Game();
            $doneGame->setUserOne($currentGame->getUserOne());
            $doneGame->setUserTwo($currentGame->getUserTwo());
            $doneGame->setGamePlay($currentGame->getGamePlay());
            $doneGame->setGameAt($currentGame->getGameAt());
            $doneGame->setResult($result);
            $entityManager->persist($doneGame);
            $entityManager->flush();
            $currentGames->remove($currentGame, true);
            dd($doneGame);
            return $this->json(['mes'=>'end'],200);
        }
        return $this->json(['mes'=>'done'],200);
    }
}
