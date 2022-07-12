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
    public function view($id, EntityManagerInterface $entityManager): Response
    {
        $currentGames = $entityManager->getRepository(CurrentGame::class);
        $currentGame = $currentGames->findOneBy(['id' => $id]);
        return $this->render('current_game/view.html.twig', [
            'currentGame' =>$currentGame,
        ]);
    }
    /**
     * @Route("/get/{id}", name="app_current_game_take")
     */
    public function getGame($id, EntityManagerInterface $entityManager): JsonResponse
    {
        $currentGames = $entityManager->getRepository(CurrentGame::class);
        $currentGame = $currentGames->findOneBy(['id' => $id]);
        return $this->json([
            'id' =>$currentGame->getId(),
            'timeUserOne' => $currentGame->getTimeUserOne(),
            'timeUserTwo' => $currentGame->getTimeUserTwo(),
        ],200);
    }
    /**
     * @Route("/lose/{id}", name="app_current_game_lose")
     */
    public function loseGame($id, EntityManagerInterface $entityManager): JsonResponse
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $currentGames = $entityManager->getRepository(CurrentGame::class);
        $currentGame = $currentGames->findOneBy(['id' => $id]);
        $users = $entityManager->getRepository(User::class);
        $user1 = $users->findOneBy(array('id' => $currentGame->getUserOne()));
        $user2 = $users->findOneBy(array('id' => $currentGame->getUserTwo()));
        if ($user !== $user1 && $user !== $user2){
            return $this->json(['mes'=>'Invalid user'],200);
        }
        $result = 1;
        $count = 8;
        if ($user == $user1){
            $result = 2;
            $count = -8;
        }
        $doneGame = new Game();
        $doneGame->setUserOne($currentGame->getUserOne());
            $doneGame->setUserTwo($currentGame->getUserTwo());
            $doneGame->setGamePlay($currentGame->getGamePlay());
            $doneGame->setGameAt($currentGame->getGameAt());
            $doneGame->setResult($result);
            $entityManager->persist($doneGame);
            $entityManager->flush();
            $currentGames->remove($currentGame, true);
            // set Elo for win user and lose user
            $user1 = $users->findOneBy(array('user' => $currentGame->getUserOne()));
            $user2 = $users->findOneBy(array('user' => $currentGame->getUserTwo()));
            $user1->setElo($user1->getElo() + $count);
            $user2->setElo($user2->getElo() - $count);
            $users->add($user1, true);
            $users->add($user2, true);
            return $this->json(['mes'=>$result],200);
    }
    /**
     * @Route("/waite/{id}", name="app_current_game_waite")
     */
    public function waite($id, EntityManagerInterface $entityManager):JsonResponse 
    {
        $currentGames = $entityManager->getRepository(CurrentGame::class);
        $currentGame = $currentGames->findOneBy(['id' => $id]);
        return $this->json(['gamePlay'=>$currentGame->getGamePlay()],200);
    }
    /**
     * @Route("/move/{id}/{move}", name="app_current_game_move")
     */
    public function move($id,$move, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
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

            // set Elo for win user and lose user
            $user1 = $users->findOneBy(array('user' => $currentGame->getUserOne()));
            $user2 = $users->findOneBy(array('user' => $currentGame->getUserTwo()));
            $count = 8;
            if ($result != 1){
                $count = -8;
            }
            $user1->setElo($user1->getElo() + $count);
            $user2->setElo($user2->getElo() - $count);
            $users->add($user1, true);
            $users->add($user2, true);
            return $this->json(['mes'=>$result],200);
        }
        return $this->json(['mes'=>'done'],200);
    }
}
