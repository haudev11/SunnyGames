<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\WaitGame;
use App\Entity\CurrentGame;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/waitGame", name="app_wait_game")
 */
class WaitGameController extends AbstractController
{
    /**
     * @Route("/", name="app_wait_game")
     */
    public function index(): Response
    {
        return $this->render('wait_game/index.html.twig', [
            'controller_name' => 'WaitGameController',
        ]);
    }
    /**
     * @Route("/joinGame/", name="app_join_game")
     */
    public function joinGame(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $game = new WaitGame();
        $game->setUserID($user);
        $game->setWaitAt(new \DateTime());
        $game->setMinElo($request->query->get('minElo'));
        $game->setMaxElo($request->query->get('maxElo'));
        $entityManager->persist($game);
        $entityManager->flush();
        return $this->json(['oke'=>'oke'],200);
    }
    /**
     * @Route("/match/", name="app_match")
     */
    public function match(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        if ($user->getCurrentGame() != NULL){
            return $this->json(['game'=>$user->getCurrentGame()->getId()],200);
        }
        // check User logined
        $minElo= $request->query->get('minElo');
        $maxElo= $request->query->get('maxElo');
        $waits = $entityManager->getRepository(WaitGame::class);
        $users = $entityManager->getRepository(User::class);
        $userWait = $waits->findMatchElo($minElo, $maxElo, $user->getId());
        if ($userWait != []) {
            $user1 = $user;
            $user2 = $users->findOneBy(['id' => $userWait[0]->getUserID()]);
            
            // remove wait game of user one and user two
            $waits->remove($waits->findOneByID($user1->getID()));
            $waits->remove($waits->findOneByID($user2->getID()));
            

            // add game for user1 and user2
            $game = new CurrentGame();
            $game->setUserOne($user1);
            $game->setUserTwo($user2);
            $game->setGamePlay('');
            $game->setTimeUserOne(600);
            $game->setTimeUserTwo(600);
            $game->setGameAt(new \DateTime);
            $entityManager->persist($game);
            $entityManager->flush();
            return $this->json(['game'=>$game->getID()],200);
        }
        return $this->json([
            'game'=>'NUll'],200);
    }
}
