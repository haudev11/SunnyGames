<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CurrentGameController extends AbstractController
{
    /**
     * @Route("/current/game", name="app_current_game")
     */
    public function index(): Response
    {
        return $this->render('current_game/index.html.twig', [
            'controller_name' => 'CurrentGameController',
        ]);
    }
}
