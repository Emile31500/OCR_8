<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class UsersVoter extends Voter
{

    private const CREATE = 'user_create';
    private const NOT_ALLOWED_MESSAGE = 'Vous n\'êtes pas autorisé à faire cette action';

    private $security;

    public function __construct(Security $security){

        $this->security = $security;

    }

    protected function supports(string $attribute, $subject): bool
    {
        
        return in_array($attribute, [self::CREATE]);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {

            throw new AccessDeniedException(self::NOT_ALLOWED_MESSAGE);

        }

        switch ($attribute) {
            case self::CREATE:

                if ($this->isUserAuthorized($user) === false){

                    throw new AccessDeniedException(self::NOT_ALLOWED_MESSAGE);

                } else {

                    return true;

                }
                break;
        }
        
        return false;
    }
    
    public function isUserAuthorized(UserInterface $user){

        return $this->security->isGranted('ROLE_ADMIN');

    }
}
