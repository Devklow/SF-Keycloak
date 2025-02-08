<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        return $this->render('default/index.html.twig', [

        ]);
    }

    #[Route('/profil', name: 'profil')]
    public function profile(UserInterface $user): Response
    {
        return $this->render('profil/index.html.twig', [
            'user'=>$user,
        ]);
    }

    #[Route('/admin', name: 'admin')]
    public function admin(UserInterface $user): Response
    {
        return $this->render('administration/index.html.twig', [
            'user'=>$user,
        ]);
    }
}
