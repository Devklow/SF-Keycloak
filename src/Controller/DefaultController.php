<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        return $this->render('default/index.html.twig', [

        ]);
    }

    #[Route('/nothing', name: 'nothing')]
    public function nothing(): Response
    {
        return $this->render('default/index.html.twig', [

        ]);
    }
}
