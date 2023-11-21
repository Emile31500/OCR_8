<?php

namespace App\Security\Voter;

use App\Entity\Task;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
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
        
        return in_array($attribute, [self::EDIT, self::DELETE])
            && $task instanceof \App\Entity\Taks;
    }

    protected function voteOnAttribute(string $attribute, $task, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case self::EDIT:

                return $this->canEdit($user, $task);
                break;

            case self::DELETE:
                return $this->canDelete($user, $task);
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
