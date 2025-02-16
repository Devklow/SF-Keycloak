<?php

namespace App\Service;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;

class RoleService
{
    private ParameterBagInterface $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    public function getRolesFromToken(ResourceOwnerInterface $userToken){
        $appli = $this->parameterBag->get('client_id');
        if (array_key_exists($appli, $userToken->toArray()['resource_access'])){
            $roles = $userToken->toArray()['resource_access'][$appli]['roles'];
        } else {
            $roles = [];
        }
        return $roles;
    }

}