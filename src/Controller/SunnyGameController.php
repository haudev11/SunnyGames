<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\CurrentGame;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SunnyGameController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(User::class)->findAll();
        $games = $entityManager->getRepository(CurrentGame::class)->findAll();
        return $this->render('sunny_game/homepage.html.twig', [
            'users' => count($users),
            'games' => count($games),
        ]);
    }
    /**
     * @Route("/users", name="app_users")
     */
    public function users(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(User::class);
        $userOrder = $users->findBy(array(), array('Elo' => 'desc'));
        return $this->render('sunny_game/users.html.twig', [
            'users' => $userOrder
        ]);
    }
}
