<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CommentVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        return $attribute == 'COMMENT' && $subject instanceof \App\Entity\User;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        return $subject instanceof UserInterface;
    }
}
