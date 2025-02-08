<?php

namespace App\Security;

use App\Entity\OldUser;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\RoleService;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class KeycloakAuthenticator extends OAuth2Authenticator implements AuthenticationEntryPointInterface
{

    /**
     * @var ClientRegistry
     */
    private $clientRegistry;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var RouterInterface
     */
    private $router;
    private RoleService $roleService;
    private UserRepository $userRepository;

    public function __construct(
        ClientRegistry $clientRegistry,
        EntityManagerInterface $entityManager,
        RouterInterface $router,
        RoleService $roleService)
    {
        $this->clientRegistry = $clientRegistry;
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->roleService = $roleService;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse(
            '/sso/connect',
            Response::HTTP_TEMPORARY_REDIRECT
        );
    }

    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === 'sso_check';
    }

    public function authenticate(Request $request): Passport
    {
        $client = $this->clientRegistry->getClient('keycloak');
        $accessToken = $this->fetchAccessToken($client);
        $keycloackUser = $client->fetchUserFromToken($accessToken);
        return new SelfValidatingPassport(
            new UserBadge($accessToken->getToken(), function () use ($accessToken, $client) {
                $keycloackUser = $client->fetchUserFromToken($accessToken);
                $roles = $this->roleService->getRolesFromToken($keycloackUser);
                $user = new User();
                $user->setRoles($roles);
                $fields = $keycloackUser->toArray();
                $user->setSub($fields['sub']);
                $user->setPrenom($fields['given_name']);
                $user->setNom($fields['family_name']);
                $user->setEmail($fields['email']);

                $this->entityManager->persist($user);
                $this->entityManager->flush();
                return $user;
            })
        );
    }

    public function onAuthenticationSuccess(
        Request $request,
        TokenInterface $token,
        string $firewallName
    ): ?Response
    {
        $targetUrl = $this->router->generate('profile');
        return new RedirectResponse($targetUrl);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $targetUrl = $this->router->generate('error');
        return new RedirectResponse($targetUrl);
    }

}