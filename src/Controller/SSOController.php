<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SSOController extends AbstractController
{
    #[Route('/sso/connect', name: 'sso_connect')]
    public function index(ClientRegistry $clientRegistry): RedirectResponse
    {
        return $clientRegistry->getClient('keycloak')->redirect();
    }

    #[Route('/sso/check', name: 'sso_check')]
    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry)
    {

    }

    #[Route('/logout', name: 'logout')]
    public function logout()
    {

    }

}