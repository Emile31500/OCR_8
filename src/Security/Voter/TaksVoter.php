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

            throw new AccessDeniedException('You\'re not allowed to edit this task');

        }

        switch ($attribute) {
            case self::EDIT:

                if ($this->canEdit($user, $task) === false){

                    throw new AccessDeniedException('You\'re not allowed to edit this task');

                } else {

                    return true;

                }
            case self::DELETE:

                if ($this->canDelete($user, $task) === false){

                    throw new AccessDeniedException('You\'re not allowed to edit this task');

                } else {

                    return true;

                }
                break;
        }
        
        return false;
    }
    
    public function canEdit(UserInterface $user, Task $task){

        if ($this->security->isGranted('ROLE_ADMIN')){

            return true;

        } else if ($user === $task->getUser()){

            return true;

        } else {

            return false;

        };

    }

    public function canDelete(UserInterface $user, Task $task){

        if ($this->security->isGranted('ROLE_ADMIN')){

            return true;

        } else if ($user === $task->getUser()){

            return true;

        } else {
            return false;
        };

    }
}
