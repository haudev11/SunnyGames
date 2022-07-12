<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\User;
use App\Entity\InviteGame;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
/**
* @Route("/user")
*/
class UserController extends AbstractController
{
    /**
     * @Route("/updateOnline",options={"option" = "export"}, name="app_user_update_online")
     */
    public function updateOnline(Request $request, EntityManagerInterface $entityManager ):JsonResponse
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $user->setOnline(new \datetime);
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->json(['oke'=>'oke'],200);
    }
    /**
     * @Route("/view/{id}", name="app_user_show")
     */
    public function show($id, EntityManagerInterface $entityManager ): Response
    {
        $user = $entityManager->getRepository(User::class)->findOneBy(array('id' => $id));
        $games = $entityManager->getRepository(Game::class)->findByUser($user);
        $invite = $entityManager->getRepository(InviteGame::class)->findBy(array('ToID'=>$user));;
        return $this->render('user/view.html.twig', [
            'user' => $user,
            'games' => $games,
            'invite' => $invite,
        ]);
    }
}
