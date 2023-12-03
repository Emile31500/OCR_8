<?php

namespace App\Security\Voter;

use App\Entity\Task;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class TaksVoter extends Voter
{

    private const EDIT = 'task_edit';
    private const DELETE = 'task_delete';
    private const NOT_ALLOWED_MESSAGE = 'Vous n\'êtes pas autorisé à faire cette action';

    private $security;

    public function __construct(Security $security){

        $this->security = $security;

    }

    protected function supports(string $attribute, $task): bool
    {
        
        return in_array($attribute, [self::EDIT, self::DELETE]) && $task instanceof Task;
    }

    protected function voteOnAttribute(string $attribute, $task, TokenInterface $token): bool
    {
        
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {

            throw new AccessDeniedException(self::NOT_ALLOWED_MESSAGE);

        }

        switch ($attribute) {
            case self::EDIT:
            case self::DELETE:

                if ($this->isUserAuthorized($user, $task) === false){

                    throw new AccessDeniedException(self::NOT_ALLOWED_MESSAGE);

                } else {

                    return true;

                }
                break;
        }
        
        return false;
    }
    
    public function isUserAuthorized(UserInterface $user, Task $task){

        return $this->security->isGranted('ROLE_ADMIN') || $user === $task->getUser();

    }
}
