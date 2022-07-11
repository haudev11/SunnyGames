<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\InviteGame;
use App\Entity\CurrentGame;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
/**
 * @Route("/invite")
 */
class InviteGameController extends AbstractController
{
    /**
     * @Route("/view/{id}/", name="app_invite_view")
     */
    public function view($id, EntityManagerInterface $entityManager): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $viewUser = $entityManager->getRepository(User::class)->findOneBy(array('id' => $id));
        if ($user !== $viewUser) {
            return $this->json(['mes'=>'Invalid user'],200);
        }
        $invites = $entityManager->getRepository(InviteGame::class)->findBy(array('ToID'=> $id));
        dd($invites);
        return $this->json(['invites'=>$invites],200);
    }
    /**
     * @Route("/cancel/", name="app_invite_cancel")
     */
    public function cancel(EntityManagerInterface $entityManager): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $invites = $entityManager->getRepository(InviteGame::class);
        $invite = $invites->findOneBy(['FromID' => $user->getId()]);
        if ($invite === NULL){
            return $this->json(['mes'=>'Null invite'],200);
        }
        $invites->remove($invite, true);
        return $this->json(['mes'=>'Done'],200);
    }
    /**
     * @Route("/accept/{id}", name="app_invite_cancel")
     */
    public function accept($id, EntityManagerInterface $entityManager): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $invites = $entityManager->getRepository(InviteGame::class);
        $invite = $invites->findOneBy(['id' => $id]);
        $user1 = $invite->getFromID();
        $user2 = $entityManager->getRepository(User::class)->findOneBy(array('id' => $invite->getToID()));
        if ($user === $user2){
            return $this->json(['mes'=>'Invalid user'],200);
        }
        $invites->remove($invite, true);
        // add game for user1 and user2
        $game = new CurrentGame();
        $game->setUserOne($user1);
        $game->setUserTwo($user2);
        $game->setGamePlay('');
        $game->setTimeUserOne(600);
        $game->setTimeUserTwo(600);
        $game->setGameAt(new \DateTime);
        $game->setLastMoveTime(new \DateTime);
        $entityManager->persist($game);
        $entityManager->flush();
        return $this->json(['mes'=>$game->getID()],200);

    }
    /**
     * @Route("/{id}/", name="app_invite_user")
     */
    public function index($id, EntityManagerInterface $entityManager): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        if ($user->getCurrentGame() != NULL){
            return $this->json(['game'=>$user->getCurrentGame()->getId()],200);
        }
        $invitedUser = $entityManager->getRepository(User::class)->findOneBy(array('id' => $id));
        if ($user === $invitedUser){
            return $this->json(['mes'=>'Invalid user'],200);
        }
        // add invite
        $invite = new InviteGame();
        $invite->setFromID($user);
        $invite->setToID($invitedUser);
        $entityManager->persist($invite);
        $entityManager->flush();
        return $this->json(['mes'=>'oke'],200);
    }
}
