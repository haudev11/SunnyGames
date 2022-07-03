<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WaitGameController extends AbstractController
{
    /**
     * @Route("/wait/game", name="app_wait_game")
     */
    public function index(): Response
    {
        return $this->render('wait_game/index.html.twig', [
            'controller_name' => 'WaitGameController',
        ]);
    }
}
