<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CustomVoter extends Voter
{

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (str_starts_with($attribute, "ROLE_")) {
            return true;
        }

        return false;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        return in_array($attribute, $user->getRoles());
    }
}