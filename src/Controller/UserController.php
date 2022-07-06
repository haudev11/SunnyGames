<?php

namespace App\Controller;

use App\Entity\User;
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
     * @Route("/updateOnline", name="app_user_update_online")
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
    public function show($id, EntityManagerInterface $entityManager ): JsonResponse
    {
        $reponsitory = $entityManager->getRepository(User::class);
        $user = $reponsitory->findOneBy(['id' => $id]);
        $elo = $user->getElo();
        $name = $user->getName();
        $email = $user->getEmail();
        $online = $user->isOnline();
        return $this->json([
            'name'=>$name,
            'email'=> $email,
            'elo'=> $elo,
            'online'=>$online, 
        ],200);
    }
}
